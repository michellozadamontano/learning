<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    //
    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function files() {
        return $this->hasMany(CourseContentFile::class);
    }
}
