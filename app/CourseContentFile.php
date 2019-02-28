<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseContentFile extends Model
{
    //
    public function courseContent(){
      return $this->belongsTo(CourseContent::class);
    }
}
