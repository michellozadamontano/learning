<?php

namespace App\Http\Controllers;

use App\Coupon;
use Epayco\Epayco;
use App\Epay_prices;
use App\PaypalSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EpayController extends Controller
{
    public function __construct() {

    }
    public function index(){        
        return Epay_prices::all();
      /* $epayco = new Epayco(array(
        "apiKey" => env('EPAY_PUBLIC_KEY'),
        "privateKey" => env('EPAY_PRIVATE_KEY'),
        "lenguage" => "ES",
        "test" => env('EPAY_TEST')
    ));
        //$sub = $epayco->subscriptions->getList();
        $customer = $epayco->customer->getList();
        dd($customer);*/
    }
    public function create_plan(){
        
		$price 			    = request('price');
        $interval_count 	= 1;
        $plan               = request('plan'); 
        if($plan == "MENSUAL") {
            $interval_count 	= 1;
        }
        if($plan == "TRIMESTRAL") {
            $interval_count 	= 3;
        }
        if($plan == "ANUAL") {
            $interval_count 	= 12;
        }        
        $epayco = new Epayco(array(
			"apiKey" => env('EPAY_PUBLIC_KEY'),
			"privateKey" => env('EPAY_PRIVATE_KEY'),
			"lenguage" => "ES",
			"test" => env('EPAY_TEST')
		));
		$planes = $epayco->plan->create(array(
			"id_plan" => $plan,
			"name" => $plan,
			"description" => $plan,
			"amount" => $price,
			"currency" => "cop",
			"interval" => "month",
			"interval_count" => $interval_count,
			"trial_days" => 0
        ));        
        $epay_plan = Epay_prices::updateOrCreate(
            ['plan_id'=> $plan],
            [
            'price'     => $price,
            'plan_id'   => $plan
        ]);
        return Epay_prices::all();
    }
    public function update_plan() {

    }
    public function epay_data() {
        $type = request('type');
        $amount = request('amount');
        return view('epay.form',compact('type','amount'));

    }
    public function epay_suscription(Request $request) {
       
       $this->validate($request,[
        'email'         => 'required|email',
        'doc_number'    => 'required',
        'card' 		    => 'required',
        'card_exp' 	    => 'required',
        'card_exp_mes' 	=> 'required',
        'card_cvc' 	    => 'required'
        
        ]);         
       
        $user = auth()->user(); 
         
        $epayco = new Epayco(array(
			"apiKey" => env('EPAY_PUBLIC_KEY'),
			"privateKey" => env('EPAY_PRIVATE_KEY'),
			"lenguage" => "ES",
			"test" => env('EPAY_TEST')
        ));
        $type = request('type');
        $token = $epayco->token->create(array(
            "card[number]"      => request('card'),
            "card[exp_year]"    => request('card_exp'),
            "card[exp_month]"   => request('card_exp_mes'),
            "card[cvc]"         => request('card_cvc'),
        )); 
        
        if($token->status){
            $customer = $epayco->customer->create(array(
                "token_card" => $token->data->id,            
                "email" => request('email'),
                "name" => request('nombre'),
                "phone" => request('phone'),
                "default" => true
            ));
            if($customer->success) {
                // mensual
                if($type == 'monthly') {
                    $price      = Epay_prices::where('plan_id','MENSUAL')->first()->price;
                    $plan       = "MENSUAL";

                    if($request->session()->has('coupon')){
                        //creo un plan nuevo para este caso
                        $plan_id = "MENSUAL". time();
                        $p_create = $epayco->plan->create(array(
                            "id_plan" => $plan_id,
                            "name" => $plan_id,
                            "description" => $plan_id,
                            "amount" => request('amount'),
                            "currency" => "cop",
                            "interval" => "month",
                            "interval_count" => 1,
                            "trial_days" => 0
                        )); 
                        $sub = $epayco->subscriptions->create(array(
                            "id_plan" => $plan_id,
                            "customer" => $customer->data->customerId,
                            "token_card" => $token->data->id,
                            "doc_type" => "CC",
                            "doc_number" => request('doc_number')
                        ));                      
                       
                        if($sub->success) {
                            $subc = $epayco->subscriptions->charge(array(
                                "id_plan" => $plan_id,
                                "customer" => $customer->data->customerId,
                                "token_card" => $token->data->id,
                                "doc_type" => "CC",
                                "doc_number" => request('doc_number')
                            )); 
                            if($subc->success && $subc->data->estado == "Aceptada") {
                                $user->paypal = 1;
                                $user->save();
                                $coupon = session('coupon');               
                                $coup = Coupon::where('code',$coupon)->first();
                                PaypalSubscription::updateOrCreate(
                                    ['user_id'  =>$user->id],
                                    [
                                        'paypal_id'     =>  $sub->id,
                                        'state'         =>  $subc->data->estado,
                                        'start_date'    =>  $sub->data->createdAt,
                                        'plan'          =>  $plan,
                                        'paypal_email'  =>  $sub->customer->email,                                    
                                        'cop'           =>  $sub->data->amount,
                                        'coupon'        =>  $coupon
                                    ]
                                );
                                if($coup){
                                    if($coup->quantity > 0)
                                    {    
                                        $coup->quantity--;
                                        $coup->save();
                                    }
                                }
                                $request->session()->forget('coupon');
                                return redirect(route('subscriptions.paypal'))
                                ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]);
                            }
                            else {
                                $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos " . $subc->data->respuesta;
                                return view('partials.errors.error',compact('title')); 
                               
                            } 

                           
                        }
                        else {
                            $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos";
                            return view('partials.errors.error',compact('title'));
                        }
                    }
                    else {
                        $sub = $epayco->subscriptions->create(array(
                            "id_plan" => $plan,
                            "customer" => $customer->data->customerId,
                            "token_card" => $token->data->id,
                            "doc_type" => "CC",
                            "doc_number" => request('doc_number')
                        ));                        
                                                        
                        if($sub->success){

                            $subc = $epayco->subscriptions->charge(array(
                                "id_plan" => $plan,
                                "customer" => $customer->data->customerId,
                                "token_card" => $token->data->id,
                                "doc_type" => "CC",
                                "doc_number" => request('doc_number')
                            ));                           
                            if($subc->success && $subc->data->estado == "Aceptada") {
                                $user->paypal = 1;
                                $user->save();
                                PaypalSubscription::updateOrCreate(
                                    ['user_id'  =>$user->id],
                                    [
                                        'paypal_id'     =>  $sub->id,
                                        'state'         =>  $subc->data->estado,
                                        'start_date'    =>  $sub->data->createdAt,
                                        'plan'          =>  $plan,
                                        'paypal_email'  =>  $sub->customer->email,                                    
                                        'cop'           =>  $sub->data->amount,                                    
                                    ]
                                );                                  
                                return redirect(route('subscriptions.paypal'))
                                ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]);
                            }
                            else {
                                $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos " . $subc->data->respuesta;
                                 return view('partials.errors.error',compact('title'));                                 
                            }                                      
                
                          
                        }
                        else
                        {
                            $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos";
                            return view('partials.errors.error',compact('title'));                            
                        }
                    }                   
                } 
                // trimestral
                if($type == 'quarterly') {
                    $price      = Epay_prices::where('plan_id','TRIMESTRAL')->first()->price;
                    $plan       = "TRIMESTRAL";

                    if($request->session()->has('coupon')){
                        //creo un plan nuevo para este caso
                        $plan_id = "TRIMESTRAL". time();
                        $p_create = $epayco->plan->create(array(
                            "id_plan" => $plan_id,
                            "name" => $plan_id,
                            "description" => $plan_id,
                            "amount" => request('amount'),
                            "currency" => "cop",
                            "interval" => "month",
                            "interval_count" => 3,
                            "trial_days" => 0
                        )); 
                        $sub = $epayco->subscriptions->create(array(
                            "id_plan" => $plan_id,
                            "customer" => $customer->data->customerId,
                            "token_card" => $token->data->id,
                            "doc_type" => "CC",
                            "doc_number" => request('doc_number')
                        ));                        
                       
                        if($sub->success) {
                            $subc = $epayco->subscriptions->charge(array(
                                "id_plan" => $plan_id,
                                "customer" => $customer->data->customerId,
                                "token_card" => $token->data->id,
                                "doc_type" => "CC",
                                "doc_number" => request('doc_number')
                            ));
                            if($subc->success && $subc->data->estado == "Aceptada") {
                                $user->paypal = 1;
                                $user->save();
                                $coupon = session('coupon');               
                                $coup = Coupon::where('code',$coupon)->first();
                                PaypalSubscription::updateOrCreate(
                                    ['user_id'  =>$user->id],
                                    [
                                        'paypal_id'     =>  $sub->id,
                                        'state'         =>  $subc->data->estado,
                                        'start_date'    =>  $sub->data->createdAt,
                                        'plan'          =>  $plan,
                                        'paypal_email'  =>  $sub->customer->email,                                    
                                        'cop'           =>  $sub->data->amount,
                                        'coupon'        =>  $coupon
                                    ]
                                );
                                if($coup){
                                    if($coup->quantity > 0)
                                    {    
                                        $coup->quantity--;
                                        $coup->save();
                                    }
                                }
                                $request->session()->forget('coupon');
                                return redirect(route('subscriptions.paypal'))
                                ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]);
                            }
                            else {
                                $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos ". $subc->data->respuesta;
                                return view('partials.errors.error',compact('title'));
                            }
                           
                        }
                        else {
                            $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos";
                            return view('partials.errors.error',compact('title'));
                        }
                    }
                    else {
                        $sub = $epayco->subscriptions->create(array(
                            "id_plan" => $plan,
                            "customer" => $customer->data->customerId,
                            "token_card" => $token->data->id,
                            "doc_type" => "CC",
                            "doc_number" => request('doc_number')
                        ));  
                                                        
                        if($sub->success){
                            $subc = $epayco->subscriptions->charge(array(
                                "id_plan" => $plan,
                                "customer" => $customer->data->customerId,
                                "token_card" => $token->data->id,
                                "doc_type" => "CC",
                                "doc_number" => request('doc_number')
                            )); 
                            if($subc->success && $subc->data->estado == "Aceptada") {
                                $user->paypal = 1;
                                $user->save();
                                PaypalSubscription::updateOrCreate(
                                    ['user_id'  =>$user->id],
                                    [
                                        'paypal_id'     =>  $sub->id,
                                        'state'         =>  $subc->data->estado,
                                        'start_date'    =>  $sub->data->createdAt,
                                        'plan'          =>  $plan,
                                        'paypal_email'  =>  $sub->customer->email,                                    
                                        'cop'           =>  $sub->data->amount,                                    
                                    ]
                                );                    
                                                   
                                return redirect(route('subscriptions.paypal'))
                                ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]);
                            }
                            else {
                                $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos ". $subc->data->respuesta;
                                return view('partials.errors.error',compact('title'));
                            }
                            
                        }
                        else
                        {
                            $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos";
                            return view('partials.errors.error',compact('title'));   
                        }
                    }                   
                } 
                // anual
                if($type == 'yearly') {
                    $price      = Epay_prices::where('plan_id','ANUAL')->first()->price;
                    $plan       = "ANUAL";

                    if($request->session()->has('coupon')){
                        //creo un plan nuevo para este caso
                        $plan_id = "ANUAL". time();
                        $p_create = $epayco->plan->create(array(
                            "id_plan" => $plan_id,
                            "name" => $plan_id,
                            "description" => $plan_id,
                            "amount" => request('amount'),
                            "currency" => "cop",
                            "interval" => "month",
                            "interval_count" => 12,
                            "trial_days" => 0
                        )); 
                        $sub = $epayco->subscriptions->create(array(
                            "id_plan" => $plan_id,
                            "customer" => $customer->data->customerId,
                            "token_card" => $token->data->id,
                            "doc_type" => "CC",
                            "doc_number" => request('doc_number')
                        ));                        
                       
                        if($sub->success) {
                            $subc = $epayco->subscriptions->charge(array(
                                "id_plan" => $plan_id,
                                "customer" => $customer->data->customerId,
                                "token_card" => $token->data->id,
                                "doc_type" => "CC",
                                "doc_number" => request('doc_number')
                            )); 
                            if($subc->success && $subc->data->estado == "Aceptada") {
                                $user->paypal = 1;
                                $user->save();
                                $coupon = session('coupon');               
                                $coup = Coupon::where('code',$coupon)->first();
                                PaypalSubscription::updateOrCreate(
                                    ['user_id'  =>$user->id],
                                    [
                                        'paypal_id'     =>  $sub->id,
                                        'state'         =>  $subc->data->estado,
                                        'start_date'    =>  $sub->data->createdAt,
                                        'plan'          =>  $plan,
                                        'paypal_email'  =>  $sub->customer->email,                                    
                                        'cop'           =>  $sub->data->amount,
                                        'coupon'        =>  $coupon
                                    ]
                                );
                                if($coup){
                                    if($coup->quantity > 0)
                                    {    
                                        $coup->quantity--;
                                        $coup->save();
                                    }
                                }
                                $request->session()->forget('coupon');
                                return redirect(route('subscriptions.paypal'))
                                ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]); 
                            }
                            else {
                                $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos ". $subc->data->respuesta;
                                return view('partials.errors.error',compact('title')); 
                            }
                            
                        }
                        else{
                            $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos";
                            return view('partials.errors.error',compact('title')); 
                        }
                    }
                    else {
                        $sub = $epayco->subscriptions->create(array(
                            "id_plan" => $plan,
                            "customer" => $customer->data->customerId,
                            "token_card" => $token->data->id,
                            "doc_type" => "CC",
                            "doc_number" => request('doc_number')
                        )); 
                                                         
                        if($sub->success){
                            $subc = $epayco->subscriptions->charge(array(
                                "id_plan" => $plan,
                                "customer" => $customer->data->customerId,
                                "token_card" => $token->data->id,
                                "doc_type" => "CC",
                                "doc_number" => request('doc_number')
                            )); 
                            if($subc->success && $subc->data->estado == "Aceptada") {
                                $user->paypal = 1;
                                $user->save();
                                PaypalSubscription::updateOrCreate(
                                    ['user_id'  =>$user->id],
                                    [
                                        'paypal_id'     =>  $sub->id,
                                        'state'         =>  $subc->data->estado,
                                        'start_date'    =>  $sub->data->createdAt,
                                        'plan'          =>  $plan,
                                        'paypal_email'  =>  $sub->customer->email,                                    
                                        'cop'           =>  $sub->data->amount,                                    
                                    ]
                                );           
                    
                               
                                return redirect(route('subscriptions.paypal'))
                                ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]);
                            }
                            else {
                                $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos ". $subc->data->respuesta;
                                return view('partials.errors.error',compact('title')); 
                            }                           
                        }
                        else
                        {
                            $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos";
                            return view('partials.errors.error',compact('title'));   
                        }
                    }                   
                } 
            }
            else
            {
                $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos";
                return view('partials.errors.error',compact('title'));   
            }
        } 
        else
        {           
            $title = "Hubo un problema con la suscripcion. Verifique que los datos sean correctos";
            return view('partials.errors.error',compact('title'));   
        }     
        
      
        
    }
}
