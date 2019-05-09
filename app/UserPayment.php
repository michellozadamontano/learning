<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    protected $fillable = ['course_id','user_id','valor','coupon','cop'];

    public function users(){
        return $this->belongsTo('App\User','user_id');
    }
    public function courses() {
        return $this->belongsTo('App\Course','course_id');
    }
}
