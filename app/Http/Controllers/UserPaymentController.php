<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\UserPayment;
use Illuminate\Http\Request;

class UserPaymentController extends Controller
{
    public function index(){
        $users = UserPayment::with('users','courses')
        ->get();        
        return $users;
    }
    public function create_ajax(Request $request){
        $this->validate($request,[
            'course_id' 		=> 'required',
            'valor' 		    => 'required',
			'user_id' 	        => 'required',
			
		]);
        $course_id 	= request('course_id');
		$valor 		= request('valor');
        $user_id 	= request('user_id');
        $coupon 	= request('coupon');
		$payment 	= UserPayment::create([
					'course_id'	=> $course_id,
					'user_id'	=> $user_id,
                    'valor' 	=> $valor,
                    'coupon'    => $coupon
				]);
    }
    public function update_ajax($id) {
        //$id = request('id');
        $payment = UserPayment::find($id);
        $payment->valor     = request('valor');
        $payment->coupon    = request('coupon');
        $payment->save();
        return $payment;
    }
    public function delete_ajax($id) {
        $payment = UserPayment::find($id);
        $payment->delete();
        return 'eliminado';
    }
    public function getUser() {
        $users = User::all();
        return $users;
    }
    public function getPayCourse() {
        $courses = Course::where('pay',1)->get();
        return $courses;
    }
}
