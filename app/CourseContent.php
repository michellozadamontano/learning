<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    public static function boot() {
        parent::boot();

        static::deleting(function($content) { // before delete() method call this
             $content->files()->delete();             
        });
    }
    //
    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function files() {
        return $this->hasMany(CourseContentFile::class);
    }
}
