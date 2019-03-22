<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PaypalSubscription extends Model
{
    //
    protected $fillable = ['user_id','paypal_id','state','start_date','plan','end_date','paypal_email','country','city','amount'];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
