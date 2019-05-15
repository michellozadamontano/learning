<?php

namespace App\Http\Controllers;

use App\Coupon;
use Epayco\Epayco;
use App\Epay_prices;
use App\PaypalPrice;
use App\Paypal\CreatePlan;
use Illuminate\Http\Request;

//require_once base_path('vendor/epayco/epayco-php/src/Epayco.php');

class SubscriptionController extends Controller
{
	public function __construct() {
		$this->middleware(function($request, $next) {
			if ( auth()->user()->paypal == 1 ) {//auth()->user()->subscribed('main')
				return redirect('/')
					->with('message', ['warning', __("Actualmente ya estás suscrito a otro plan")]);
			}
			return $next($request);
		})
		->only(['plans', 'processSubscription']);
	}

	public function plans () {	
		$money 				= request('money');	
		$plan_mensual 		= PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price;
		$plan_trimestral 	= PaypalPrice::with('paypal_plan')->where('plan_id',2)->first()->price;
		$plan_anual 		= PaypalPrice::with('paypal_plan')->where('plan_id',3)->first()->price;

		$epay_mensual 		= Epay_prices::where('plan_id','MENSUAL')->first()->price;
		$epay_trimestral    = Epay_prices::where('plan_id','TRIMESTRAL')->first()->price;
		$epay_anual 		= Epay_prices::where('plan_id','ANUAL')->first()->price;

		if($money == "usd"){
			return view('subscriptions.plans',compact('plan_mensual','plan_trimestral','plan_anual'));
		}
		else {
			return view('subscriptions.epay_plans',compact('epay_mensual','epay_trimestral','epay_anual'));
		}
		
	}
	public function plan_epay(){
		return view('subscriptions.money');
	}

    public function processSubscription () {
	    $token = request('stripeToken');
	    try {
			if ( \request()->has('coupon')) {
				\request()->user()->newSubscription('main', \request('type'))
					->withCoupon(\request('coupon'))->create($token);
			} else {
				\request()->user()->newSubscription('main', \request('type'))
				          ->create($token);
			}
		    return redirect(route('subscriptions.admin'))
			    ->with('message', ['success', __("La suscripción se ha llevado a cabo correctamente")]);
	    } catch (\Exception $exception) {
	    	$error = $exception->getMessage();
	    	return back()->with('message', ['danger', $error]);
	    }
    }

    public function admin () {
		$subscriptions = auth()->user()->subscriptions;
		return view('subscriptions.admin', compact('subscriptions'));
    }

    public function resume () {
		$subscription = \request()->user()->subscription(\request('plan'));
		if ($subscription->cancelled() && $subscription->onGracePeriod()) {
			\request()->user()->subscription(\request('plan'))->resume();
			return back()->with('message', ['success', __("Has reanudado tu suscripción correctamente")]);
		}
		return back();
    }

    public function cancel () {
		auth()->user()->subscription(\request('plan'))->cancel();
	    return back()->with('message', ['success', __("La suscripción se ha cancelado correctamente")]);
		}
		
		//Funciones de paypal 
		public function createPlan() {
			$plan = new CreatePlan();

			$plan->create();
		}
		public function paypalRedirect(Request $request) {
			$type 		= request('type');
			$coupon 	= request('coupon');
			$price 		= 0;
			$amount 	= 0;
			$descuento 	= 0;
			if(\request('type') == 'monthly')
			{
				$price      = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price; 
				$amount     = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price;
				//Compruebo se se introdujo un coupon
				if(request('coupon')!= ""){
					$coupon = request('coupon');
					
				//valido el coupon que sea correcto y que tenga existencia
					if($this->checkCoupon($coupon)){
						//session(['coupon' => $coupon]); // almaceno el coupon en una session para una vez que se confirme la subscripcion rebajarlo del total
						//ahora aplico el descuento para este coupon
						$price      = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price;                    
						$percent    = Coupon::where('code',$coupon)->first()->percent;
						$new_price  = $price - ($price * $percent);	
						$amount 	= $new_price;	
						$descuento  = $price * $percent;			
					}
					else{
						return back()->with('message', ['danger', __("Cupon No Valido")]);
					}
				}				
			
			}
			if(\request('type') == 'quarterly')
			{
				$price      = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price; 
				$amount     = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price;
				//Compruebo se se introdujo un coupon
				if(request('coupon')!= ""){
					$coupon = request('coupon');
					
				//valido el coupon que sea correcto y que tenga existencia
					if($this->checkCoupon($coupon)){
						
						//ahora aplico el descuento para este coupon
						$price      = PaypalPrice::with('paypal_plan')->where('plan_id',2)->first()->price;                    
						$percent    = Coupon::where('code',$coupon)->first()->percent;
						$new_price  = $price - ($price * $percent);	
						$amount 	= $new_price;	
						$descuento  = $price * $percent;					
					}
					else{
						return back()->with('message', ['danger', __("Cupon No Valido")]);
					}
				}				
				
			}
			if(\request('type') == 'yearly')
			{
				$price      = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price; 
				$amount     = PaypalPrice::with('paypal_plan')->where('plan_id',1)->first()->price;
				//Compruebo se se introdujo un coupon
				if(request('coupon')!= ""){
					$coupon = request('coupon');
					
				//valido el coupon que sea correcto y que tenga existencia
					if($this->checkCoupon($coupon)){
						
						//ahora aplico el descuento para este coupon
						$price      = PaypalPrice::with('paypal_plan')->where('plan_id',3)->first()->price;                    
						$percent    = Coupon::where('code',$coupon)->first()->percent;
						$new_price  = $price - ($price * $percent);	
						$amount 	= $new_price;	
						$descuento  = $price * $percent;					
					}
					else{
						return back()->with('message', ['danger', __("Cupon No Valido")]);
					}
				}	
			
			}
			
			return view('subscriptions.paypal_pay',compact('type','coupon','price','amount','descuento'));
		}
		public function epayRedirect() {
			$type 		= request('type');
			$coupon 	= request('coupon');
			$price 		= 0;
			$amount 	= 0;
			$descuento 	= 0;
			if(\request('type') == 'monthly')
			{
				$price      = Epay_prices::where('plan_id','MENSUAL')->first()->price;
				$amount     = Epay_prices::where('plan_id','MENSUAL')->first()->price;
				//Compruebo se se introdujo un coupon
				if(request('coupon')!= ""){
					$coupon = request('coupon');
					session(['coupon' => $coupon]);
				//valido el coupon que sea correcto y que tenga existencia
					if($this->checkCoupon($coupon)){
						//session(['coupon' => $coupon]); // almaceno el coupon en una session para una vez que se confirme la subscripcion rebajarlo del total
						//ahora aplico el descuento para este coupon
						$price      = Epay_prices::where('plan_id','MENSUAL')->first()->price;                    
						$percent    = Coupon::where('code',$coupon)->first()->percent;
						$new_price  = $price - ($price * $percent);	
						$amount 	= $new_price;	
						$descuento  = $price * $percent;			
					}
					else{
						return back()->with('message', ['danger', __("Cupon No Valido")]);
					}
				}				
			
			}
			if(\request('type') == 'quarterly')
			{
				$price      = Epay_prices::where('plan_id','TRIMESTRAL')->first()->price;
				$amount     = Epay_prices::where('plan_id','TRIMESTRAL')->first()->price;
				//Compruebo se se introdujo un coupon
				if(request('coupon')!= ""){
					$coupon = request('coupon');
					session(['coupon' => $coupon]);
				//valido el coupon que sea correcto y que tenga existencia
					if($this->checkCoupon($coupon)){
						
						//ahora aplico el descuento para este coupon
						$price      = Epay_prices::where('plan_id','TRIMESTRAL')->first()->price;                    
						$percent    = Coupon::where('code',$coupon)->first()->percent;
						$new_price  = $price - ($price * $percent);	
						$amount 	= $new_price;	
						$descuento  = $price * $percent;					
					}
					else{
						return back()->with('message', ['danger', __("Cupon No Valido")]);
					}
				}				
				
			}
			if(\request('type') == 'yearly')
			{
				$price      = Epay_prices::where('plan_id','ANUAL')->first()->price;
				$amount     = Epay_prices::where('plan_id','ANUAL')->first()->price;
				//Compruebo se se introdujo un coupon
				if(request('coupon')!= ""){
					$coupon = request('coupon');
					session(['coupon' => $coupon]);
				//valido el coupon que sea correcto y que tenga existencia
					if($this->checkCoupon($coupon)){
						
						//ahora aplico el descuento para este coupon
						$price      = Epay_prices::where('plan_id','ANUAL')->first()->price;                   
						$percent    = Coupon::where('code',$coupon)->first()->percent;
						$new_price  = $price - ($price * $percent);	
						$amount 	= $new_price;	
						$descuento  = $price * $percent;					
					}
					else{
						return back()->with('message', ['danger', __("Cupon No Valido")]);
					}
				}	
			
			}
			
			return view('subscriptions.epay_pay',compact('type','coupon','price','amount','descuento'));
		}
		public function checkCoupon($coupon){
			$result = false;
			$coup = Coupon::where('code',$coupon)->first();
			if($coup){
				if($coup->quantity > 0)
				{
					$result = true;
				   // $coup->quantity--;
					$coup->save();
				}
			}
	
			return $result;
		}
		
}
