<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Used to process plans
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Api\Payer;
use PayPal\Api\ShippingAddress;
use App\PaypalSubscription;
use App\Course;
use Illuminate\Support\Facades\Route;
use App\PaypalPlan;
use App\PaypalPrice;
use App\Coupon;
use App\User;


class PaypalController extends Controller
{
    //
    private $apiContext;
    private $mode;
    private $client_id;
    private $secret;
    private $plan_id;
    private $plan_id_trimestral;
    private $plan_id_anual;

    public function __construct()
    {
        $this->client_id            = config('services.paypal.id') ;
        $this->secret               = config('services.paypal.secret');
        $this->plan_id              = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->paypal_code; //env('PAYPAL_PLAN_ID');
        $this->plan_id_trimestral   = PaypalPrice::with('paypal_plan')->where('plan_id',2)->first()->paypal_code; //env('PAYPAL_PLAN_ID_2');
        $this->plan_id_anual        = PaypalPrice::with('paypal_plan')->where('plan_id',3)->first()->paypal_code; //env('PAYPAL_PLAN_ID_3');


        // Set the Paypal API Context/Credentials
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->apiContext->setConfig(config('services.paypal.settings'));        

    }

    public function create_plan(){

        // Create a new billing plan
        $route = request()->root(); 
        $frequency_interval = request('plan');
        $price = request('price'); 

        $plan = new Plan();
        $plan->setName('Subscription to Edwin Course')
          ->setDescription('Monthly Subscription to Edwin Course')
          ->setType('infinite');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
          ->setType('REGULAR')
          ->setFrequency('Month')
          ->setFrequencyInterval($frequency_interval)
          ->setCycles('0')
          ->setAmount(new Currency(array('value' => $price, 'currency' => 'USD')));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl($route.'/subscriptions/subscribe/paypal/return')
          ->setCancelUrl($route.'/subscriptions/subscribe/paypal/cancel')
          ->setAutoBillAmount('yes')
          ->setInitialFailAmountAction('CONTINUE')
          ->setMaxFailAttempts('0');

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        //create the plan
        try {
            $createdPlan = $plan->create($this->apiContext);

            try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                  ->setPath('/')
                  ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->apiContext);
                $plan = Plan::get($createdPlan->getId(), $this->apiContext);

                // Output plan id
               // echo 'Plan ID:' . $plan->getId();
                $paypalPrice                = PaypalPrice::find($frequency_interval);
                $paypalPrice->price         = $price;
                $paypalPrice->paypal_code   = $plan->getId();
                $paypalPrice->save();
                
                $prices     = PaypalPrice::with('paypal_plan')->get();
                $response   = ['price' => $paypalPrice,'prices' => $prices];
                return response()->json($response);
                //return response()->json($plan->getId());
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                return response()->json($ex->getCode());
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } catch (Exception $ex) {
                die($ex);
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json($ex->getCode());
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            return response()->json($ex);
            die($ex);
        }

    }
    public function paypalRedirect(){
        $route = request()->root();
        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('App Name Monthly Subscription Agreement')
          ->setDescription('Basic Subscription')
          ->setStartDate(\Carbon\Carbon::now()->addMinutes(5)->toIso8601String());

        // Set plan id
        $plan = new Plan();
        if(\request('type') == 'monthly')
        {
            //Compruebo se se introdujo un coupon
            if(request()->has('coupon')){
                $coupon = request('coupon');
               //valido el coupon que sea correcto y que tenga existencia
                if($this->checkCoupon($coupon)){
                    //ahora aplico el descuento para este coupon
                    $price      = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price;                    
                    $percent    = Coupon::where('code',$coupon)->first()->percent;
                    $new_price  = $price - ($price * $percent);
                    //ahora genero un nuevo plan id para este nuevo precio y en esta membresia
                    $plan_coupon_monthly_id = $this->getPlanIdByCoupon($new_price,1,$route);                    
                    $plan->setId($plan_coupon_monthly_id);
                }
                else{
                    return back()->with('message', ['danger', __("Cupon No Valido")]);
                }
            }
            else {
                $plan->setId($this->plan_id);
            }
           
        }
        if(\request('type') == 'quarterly')
        {
             //Compruebo se se introdujo un coupon
             if(request()->has('coupon')){
                $coupon = request('coupon');
               //valido el coupon que sea correcto y que tenga existencia
                if($this->checkCoupon($coupon)){
                    //ahora aplico el descuento para este coupon
                    $price      = PaypalPrice::with('paypal_plan')->where('plan_id',2)->first()->price;                    
                    $percent    = Coupon::where('code',$coupon)->first()->percent;
                    $new_price  = $price - ($price * $percent);
                    //ahora genero un nuevo plan id para este nuevo precio y en esta membresia
                    $plan_coupon_monthly_id = $this->getPlanIdByCoupon($new_price,3,$route);                    
                    $plan->setId($plan_coupon_monthly_id);
                }
                else{
                    return back()->with('message', ['danger', __("Cupon No Valido")]);
                }
            }
            else{
                $plan->setId($this->plan_id_trimestral);
            }
            
        }
        if(\request('type') == 'yearly')
        {
             //Compruebo se se introdujo un coupon
             if(request()->has('coupon')){
                $coupon = request('coupon');
               //valido el coupon que sea correcto y que tenga existencia
                if($this->checkCoupon($coupon)){
                    //ahora aplico el descuento para este coupon
                    $price      = PaypalPrice::with('paypal_plan')->where('plan_id',3)->first()->price;                    
                    $percent    = Coupon::where('code',$coupon)->first()->percent;
                    $new_price  = $price - ($price * $percent);
                    //ahora genero un nuevo plan id para este nuevo precio y en esta membresia
                    $plan_coupon_monthly_id = $this->getPlanIdByCoupon($new_price,12,$route);                    
                    $plan->setId($plan_coupon_monthly_id);
                }
                else{
                    return back()->with('message', ['danger', __("Cupon No Valido")]);
                }
            }
            else {
                $plan->setId($this->plan_id_anual);
            }
           
        }
       
        $agreement->setPlan($plan);

        // Add payer type
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        try {
          // Create agreement
          $agreement = $agreement->create($this->apiContext);

          // Extract approval URL to redirect user
          $approvalUrl = $agreement->getApprovalLink();

          return redirect($approvalUrl);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
          echo $ex->getCode();
          echo $ex->getData();
          die($ex);
        } catch (Exception $ex) {
          die($ex);
        }

    }
    public function paypalReturn(Request $request){
        $token = $request->token;
        $agreement = new \PayPal\Api\Agreement();
        //dd($token);
        try {
            // Execute agreement
            $result = $agreement->execute($token, $this->apiContext);
            $user = auth()->user();           
            $user->paypal = 1;
            if(isset($result->id)){
                $user->paypal_agreement_id = $result->id;
            }
            $user->save();
          //  dd($result->plan->payment_definitions->type);
            $plan = $result->plan;
            $payer = $result->payer;     

           
            PaypalSubscription::updateOrCreate(
                ['user_id'  =>$user->id],
                [
                    'paypal_id'     =>  $result->id,
                    'state'         =>  $result->state,
                    'start_date'    =>  $result->start_date,
                    'plan'          =>  "REGULAR",
                    'paypal_email'  =>  $payer->payer_info->email,
                    'country'       =>  $payer->payer_info->shipping_address->country_code,
                    'city'          =>  $payer->payer_info->shipping_address->city
                ]
            );
           // dd($result);
            return redirect(route('subscriptions.paypal'))
            ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]);
           // echo 'New Subscriber Created and Billed';

        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo 'Hubo un problema con la subscriccion' + $ex;
        }
    }
    public function pyaplCancel(Request $request){
        dd($request);
        
    }
    public function paypalSuspend(){
       
        $plan = request('plan');
        $agreementId = $plan;                  
        $agreement = new Agreement();            

        $agreement->setId($agreementId);        
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Suspend the agreement");

        try {
            $agreement->suspend($agreementStateDescriptor, $this->apiContext);
            $cancelAgreementDetails = Agreement::get($agreement->getId(), $this->apiContext); 
            $user = auth()->user();
            $user->paypal = 0;
            $user->save();
            $subcription = PaypalSubscription::where('user_id',$user->id)->first();
            $subcription->state = $cancelAgreementDetails->getState();
            $subcription->save();
        /*voy a desacioar todos los curos a los que esta inscrito este estudiante, si reanuda la subcripcion
         tiene que inscribirse de nuevo*/
            $user->student->courses()->detach(); 
            

            
           // dd($cancelAgreementDetails);      
           return redirect(route('subscriptions.paypal'))
            ->with('message', ['success', __("La suscripción se ha suspendido correctamente")]);
        } catch (Exception $ex) {  
            echo 'Hubo un problema al suspender la subscriccion';                
        }
        
    }
    public function paypalCancel(){        
        $plan = request('plan');
        $agreementId = $plan;                  
        $agreement = new Agreement();            

        $agreement->setId($agreementId);        
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Cancel the agreement");

        try {
            $agreement->cancel($agreementStateDescriptor, $this->apiContext);
            $cancelAgreementDetails = Agreement::get($agreement->getId(), $this->apiContext); 
           // dd($cancelAgreementDetails);
            $user = auth()->user();
            $user->paypal = 0;
            $user->save();
            $subcription = PaypalSubscription::where('user_id',$user->id)->first();
            $subcription->delete();
           // $subcription->state = $cancelAgreementDetails->getState();
          //  $subcription->save();
        /*voy a desacioar todos los curos a los que esta inscrito este estudiante, si reanuda la subcripcion
         tiene que inscribirse de nuevo*/
            $user->student->courses()->detach(); 
            

            
           // dd($cancelAgreementDetails);      
           return redirect(route('subscriptions.paypal'))
            ->with('message', ['success', __("La suscripción se ha cancelado correctamente")]);
        } catch (Exception $ex) {  
            echo 'Hubo un problema al cancelar la subscriccion';                
        }
        
    }
    public function paypalReactivate(){
        $plan = request('plan');
        $agreementId = $plan;                  
        $agreement = new Agreement();            

        $agreement->setId($agreementId);        
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Reactivate the agreement");

        try {
            $agreement->reActivate($agreementStateDescriptor, $this->apiContext);
            $agreementDetails = Agreement::get($agreement->getId(), $this->apiContext); 
            $user = auth()->user();
            $user->paypal = 1;
            $user->save();
            $subcription = PaypalSubscription::where('user_id',$user->id)->first();            
            $subcription->state = $agreementDetails->getState();
            $subcription->save();
            
           // dd($cancelAgreementDetails);      
           return redirect(route('subscriptions.paypal'))
            ->with('message', ['success', __("La suscripción se ha reactivado correctamente")]);
        } catch (Exception $ex) {  
            echo 'Hubo un problema al suspender la subscriccion';                
        }
        
    }
    public function admin(){
        $subscription = auth()->user()->paypalSubscription;
        //dd($subscription);
        return view('subscriptions.paypal', compact('subscription'));
    }

    public function paypalPlans()
    {
        $plans = PaypalPlan::with('paypal_price')->get();
        $prices = PaypalPrice::with('paypal_plan')->get();
        $response = ['plans' => $plans,'prices' => $prices];
        return response()->json($response);
    }
    public function checkCoupon($coupon){
        $result = false;
        $coup = Coupon::where('code',$coupon)->first();
        if($coup){
            if($coup->quantity > 0)
            {
                $result = true;
                $coup->quantity--;
                $coup->save();
            }
        }

        return $result;
    }

    public function getPlanIdByCoupon($price,$frequency,$route){
        $plan = new Plan();
        $plan->setName('Subscription to Edwin Course')
          ->setDescription('Monthly Subscription to Edwin Course')
          ->setType('infinite');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
          ->setType('REGULAR')
          ->setFrequency('Month')
          ->setFrequencyInterval($frequency)
          ->setCycles('0')
          ->setAmount(new Currency(array('value' => $price, 'currency' => 'USD')));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl($route.'/subscriptions/subscribe/paypal/return')
          ->setCancelUrl($route.'/subscriptions/subscribe/paypal/cancel')
          ->setAutoBillAmount('yes')
          ->setInitialFailAmountAction('CONTINUE')
          ->setMaxFailAttempts('0');

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        //create the plan
        try {
            $createdPlan = $plan->create($this->apiContext);

            try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                  ->setPath('/')
                  ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->apiContext);
                $plan = Plan::get($createdPlan->getId(), $this->apiContext);

                
                return $plan->getId();
                
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                return response()->json($ex->getCode());
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } catch (Exception $ex) {
                die($ex);
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json($ex->getCode());
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            return response()->json($ex);
            die($ex);
        }

    }

    // aqui voy a obtener los usuarios subscritos
    public function getUserSubscribed(){
        $users = User::where('paypal',1)->count();
        return $users;
    }
 
}
