<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mail\MessageToStudent;
use App\Student;
use App\User;

class TeacherController extends Controller
{
	public function courses () {
		$courses = Course::withCount(['students'])->with('category', 'reviews')
			->whereTeacherId(auth()->user()->teacher->id)->paginate(12);
		return view('teachers.courses', compact('courses'));
	}

    public function students () {
		$students = Student::with('user', 'courses.reviews')
			->whereHas('courses', function ($q) {
				$q->where('teacher_id', auth()->user()->teacher->id)->select('id', 'teacher_id', 'name')->withTrashed();
			})->get();

		$actions = 'students.datatables.actions';
		return \DataTables::of($students)->addColumn('actions', $actions)->rawColumns(['actions', 'courses_formatted'])->make(true);
    }

    public function sendMessageToStudent () {
    	$info = \request('info');
    	$data = [];
    	parse_str($info, $data);
    	$user = User::findOrFail($data['user_id']);
    	try {
    		\Mail::to($user)->send(new MessageToStudent( auth()->user()->name, $data['message']));
    		$success = true;
	    } catch (\Exception $exception) {
    		$success = false;
	    }
    	return response()->json(['res' => $success]);
	}
	//estos metodos son para consumirlos desde vuejs
	public function getCourses() {
		$teacher = request('teacher_id');
		$courses = Course::where('teacher_id',$teacher)->get();
		return $courses;
	}
	public function getStudent() {
		$course_id = request('course_id');
		$students = User::with('student')->whereHas('student.courses', function($q) use($course_id){
			$q->where('id',$course_id);
		})->get();
		return $students;

	}
	public function sendMessage() {
		$course_id 	= request('course_id');
		$message 	= request('message');
		$students 	= User::with('student')->whereHas('student.courses', function($q) use($course_id){
			$q->where('id',$course_id);
		})->get();		
		try {
			\Mail::to($students)->send(new MessageToStudent( auth()->user()->name, $message));
			/*foreach ($students as $value) {
				
			}*/
    		
    		$success = true;
	    } catch (\Exception $exception) {
    		$success = false;
		}
		return response()->json(['res' => $success]);

	}
}
