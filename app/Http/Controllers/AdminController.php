<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mail\CourseApproved;
use App\Mail\CourseRejected;
use App\VueTables\EloquentVueTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Teacher;
use App\Student;
use App\User;
use App\CourseContentFile;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
	public function courses () {
		return view('admin.courses');
	}
	public function paypal () {
		return view('admin.paypal');
	}

	public function coursesJson () {
		if(request()->ajax()) {
			$vueTables = new EloquentVueTables;
			$data = $vueTables->get(new Course, ['id', 'name', 'status','slug'], ['reviews']);
			return response()->json($data);
		}
		return abort(401);
	}
	public function coursesExcel() {
		$courses = Course::all();
		return $courses;
	}
	public function teachersJson() {
	//	if(request()->ajax()) {
			$vueTables = new EloquentVueTables;
			//$data = $vueTables->get(new User, ['id', 'name','email'], ['teacher.courses']);
			//$data = User::with('teacher.courses')->get();
		//	$data = Teacher::with('user','courses')->get();
		//	return response()->json($data);
		return Teacher::with('user','courses')->get();
	//	}
	//	return abort(401);
	}

	public function updateCourseStatus () {
		if (\request()->ajax()) {
			$course = Course::find(\request('courseId'));

			if(
				(int) $course->status !== Course::PUBLISHED &&
				! $course->previous_approved &&
				\request('status') === Course::PUBLISHED
			) {
				$course->previous_approved = true;
			//	\Mail::to($course->teacher->user)->send(new CourseApproved($course));
			}

			if(
				(int) $course->status !== Course::REJECTED &&
				! $course->previous_rejected &&
				\request('status') === Course::REJECTED
			) {
				$course->previous_rejected = true;
			//	\Mail::to($course->teacher->user)->send(new CourseRejected($course));
			}

			$course->status = \request('status');
			$course->save();
			return response()->json(['msg' => 'ok']);
		}
		return abort(401);
	}
	public function showCourseContent(){
		//esta funcion la estoy llamando desde vue para mostrar el contenido de los cursos		
		if(request()->ajax()){
			$course = Course::find(\request('courseId'));
			
			try {
				//code...
				$course->load([	
					'courseContent.files'
				])->get();			
		
				return response()->json($course);
			} catch (\Exception $exception) {
				return response()->json($exception);
			}
		}
		return abort(401);
	}
	public function showContentFiles(){
		if(request()->ajax()){
			$content = CourseContentFile::find(request('contentId'));
			return response()->json($content);
		}
		return abort(401);
	}

	public function students () {	
		
	/*	$students = Student::with('user', 'courses.reviews')
		->whereHas('courses', function ($q) {
			$q->where('status', Course::PUBLISHED)->select('id', 'teacher_id', 'name')->withTrashed();
		})->get();
		foreach ($students as $key => $value) {
			# code...
			dd($value->courses->teacher);
		}*/
		return view('admin.students');
	}
	public function dataStudent () {
		$students = Student::with('user.paypalSubscription', 'courses.reviews')
		->whereHas('courses', function ($q) {
			$q->where('status', Course::PUBLISHED)->select('id', 'teacher_id', 'name')->withTrashed();
		})->get();
		return response()->json($students);
	//dd($students);
		return DataTables::of($students)
		->addColumn('plan',function(Student $student){
			return $student->user->paypalsubscription->plan;
		})
		->addColumn('start_date',function(Student $student){
			return $student->user->paypalsubscription->start_date;
		})
		->addColumn('end_date',function(Student $student){
			return $student->user->paypalsubscription->end_date;
		})
		->rawColumns(['courses_formatted'])->make(true);
	}

	public function teachers () {
		$teachers = Teacher::all();		
		return view('admin.teachers');
	}
	public function traiding($value = "")
	{
		$pepe = $value . " edwing colombia";
		return view('admin.traiding',compact('pepe'));
	}
	
}
