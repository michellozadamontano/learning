<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalPlan extends Model
{
    public function paypal_price()
    {
        return $this->hasOne(PaypalPrice::class,'plan_id');
    }
}
