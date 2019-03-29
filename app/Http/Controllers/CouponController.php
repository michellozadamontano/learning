<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;

class CouponController extends Controller
{
    public function index(){
        return Coupon::latest()->get();
    }
    public function store(Request $request)
    {

        $this->validate($request,[
            'code' => 'required|string|max:20',
            'quantity' => 'required',
            'percent' => 'required'
        ]);

        return Coupon::updateOrCreate(
            ['code'  =>$request['code']],
            [
                'code' => $request['code'],
                'quantity' => $request['quantity'],
                'percent' => $request['percent'],           
            ]
        );

    }
    public function update(Request $request, $id)
    {

        $coupon = Coupon::findOrFail($id);

        $this->validate($request,[
            'code' => 'required|string|max:20',
            'quantity' => 'required',
            'percent' => 'required'
        ]);

        $coupon->update($request->all());
        return ['message' => 'Coupon Actualizado'];
    }
    public function destroy($id)
    {   

        $coupon = Coupon::findOrFail($id);

        $coupon->delete();

        return ['message' => 'Coupon Eliminado'];
    }
}

