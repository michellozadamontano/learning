<?php

namespace App\Http\Controllers;

use App\Course;
use App\Helpers\Helper;
use App\Http\Requests\CourseRequest;
use App\Mail\NewStudentInCourse;
use App\Review;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpFoundation\Request;
use App\CourseContent;
use App\CourseContentFile;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
	public function show (Course $course) {
		$course->load([
			'category' => function ($q) {
				$q->select('id', 'name');
			},
			'goals' => function ($q) {
				$q->select('id', 'course_id', 'goal');
			},
			'level' => function ($q) {
				$q->select('id', 'name');
			},
			'requirements' => function ($q) {
				$q->select('id', 'course_id', 'requirement');
			},
			'reviews.user',
			'teacher'
		])->get();

		$related = $course->relatedCourses();

		return view('courses.detail', compact('course', 'related'));
	}

	public function inscribe (Course $course) {
		$course->students()->attach(auth()->user()->student->id);

	//	\Mail::to($course->teacher->user)->send(new NewStudentInCourse($course, auth()->user()->name));

		return back()->with('message', ['success', __("Inscrito correctamente al curso")]);
	}

	public function subscribed () {
		$courses = Course::whereHas('students', function($query) {
			$query->where('user_id', auth()->id());
		})->get();
		return view('courses.subscribed', compact('courses'));
	}

	public function addReview () {
		Review::create([
			"user_id" => auth()->id(),
			"course_id" => request('course_id'),
			"rating" => (int) request('rating_input'),
			"comment" => request('message')
		]);
		return back()->with('message', ['success', __('Muchas gracias por valorar el curso')]);
	}

	public function create () {
		$course = new Course;
		$btnText = __("Enviar curso para revisión");
		return view('courses.form', compact('course', 'btnText'));
	}

	public function store (CourseRequest $course_request) {		
	
		$picture = Helper::uploadFile('picture', 'courses');
		$course_request->merge(['picture' => $picture]);
		$course_request->merge(['teacher_id' => auth()->user()->teacher->id]);
		$course_request->merge(['status' => Course::PENDING]);
		Course::create($course_request->input());
		return back()->with('message', ['success', __('Curso enviado correctamente, recibirá un correo con cualquier información')]);
	}

	public function edit ($slug) {
		$course = Course::with(['requirements', 'goals'])->withCount(['requirements', 'goals'])
			->whereSlug($slug)->first();
		$btnText = __("Actualizar curso");
		return view('courses.form', compact('course', 'btnText'));
	}

	public function update (CourseRequest $course_request, Course $course) {
		if($course_request->hasFile('picture')) {
			\Storage::delete('courses/' . $course->picture);
			$picture = Helper::uploadFile( "picture", 'courses');
			$course_request->merge(['picture' => $picture]);
		}
		$course->fill($course_request->input())->save();
		return back()->with('message', ['success', __('Curso actualizado')]);
	}

	public function destroy (Course $course) {
		try {
			$course->delete();
			return back()->with('message', ['success', __("Curso eliminado correctamente")]);
		} catch (\Exception $exception) {
			return back()->with('message', ['danger', __("Error eliminando el curso")]);
		}
	}

	//aqui voy a mostrar el contenido del curso
	public function showContent(Course $course) {
		
		try {
			//code...
			$course->load([	
				'courseContent'
			])->get();		
			//dd($course);
			
			return view('courses.content', compact('course'));
		} catch (\Exception $exception) {
			return back()->with('message', ['danger', __("Error al mostrar los datos")]);
		}
	}
	public function showVideo($id)
	{
		$file = CourseContentFile::find($id);		
		$content = CourseContent::find($file->course_content_id);		
		$course = Course::find($content->course_id);		
				
		return view('courses.show_video', compact('file','course'));
	}
	//este metodo es para agregar el titulo de la clase al curso
	public function addContent(Course $course) {
		$btnText = __("Adicionar");
		$clases = $course->courseContent;
		return view('courses.content_form',compact('course','btnText', 'clases'));
	}
	public function addContentAction(Request $request){
		$request->validate([
			'titulo' => 'required|max:255',			
		]);
		$content = new CourseContent();
		$content->titulo = $request->get('titulo');
		$content->course_id = $request->get('course_id');
		$content->save();
		return back();
	}
	public function addContentFile(Request $request) {
		//dd($request);
		//$request->file('video')->isValid();

		//$document = $request->file('archivo');
		$content_file = new CourseContentFile();
		if($request->file('archivo') != null)
		{
			$request->validate([
				'archivo' => 'mimes:pdf,txt,docx',
			]);
			$archivo = Helper::uploadFile('archivo', 'courses');
			$content_file->arhivo = $archivo;
		}

		if(request('url_vimeo') == null && request('url_youtube') == null && $request->file('archivo') == null)
		{
			return back()->with('message', ['danger', __("Al menos tiene que existir o un video o un archivo")]);
		}
		
		
		$content_file->course_content_id = $request->get('titulo_id');
		$content_file->file = request('titulo_video'); //$document->getClientOriginalName();
		
		
		if(request('video_radio') == "youtube" && request('url_youtube') != null)
		{
			$url = request('url_youtube');
			$exp="/v\/?=?([0-9A-Za-z-_]{11})/is";
			$result = preg_match_all( $exp , $url , $matches );
			//dd($result);
			if($result){
				$id = $matches[1][0];			
				$content_file->path = $id;
			}
			else {
				return back()->with('message', ['danger', __("La url no es correcta")]);
			}
			
			//$content_file->save();
		}
		if(request('video_radio') == "vimeo" && request('url_vimeo') != null)
		{
			$url = request('url_vimeo');
			$exp="/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/";
			$result = preg_match_all( $exp , $url , $matches );
			if($result){
				$id = $matches[5][0];
				$content_file->path = $id;
			}
			else {
				return back()->with('message', ['danger', __("La url no es correcta")]);
			}
			
			//$content_file->save();
		}
		$content_file->save();
		
		return back()->with('message', ['success', __("Datos subidos correctamente")]);
	}
	public function download($file){
		$file_path = public_path('images/courses/'.$file);
		return response()->download($file_path);
	}
}
