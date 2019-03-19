<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->check()){
            if(auth()->user()->role->name == "admin"){
                return view('admin.index');
            } 
            else{
                $courses = Course::withCount(['students'])
                ->with('category', 'teacher', 'reviews')
                ->where('status', Course::PUBLISHED)
                ->latest()
                ->paginate(12);
    
            return view('home', compact('courses'));
            }
        }       
        else{
            $courses = Course::withCount(['students'])
		    ->with('category', 'teacher', 'reviews')
		    ->where('status', Course::PUBLISHED)
		    ->latest()
		    ->paginate(12);

        return view('home', compact('courses'));
        }
       
    }
}
