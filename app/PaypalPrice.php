<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PaypalPlan;

class PaypalPrice extends Model
{
    public function paypal_plan()
    {
        return $this->belongsTo('App\PaypalPlan','plan_id');
    }
}
