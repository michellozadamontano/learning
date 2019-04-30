<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Course;
use App\Review;
use App\UserPayment;
use App\CourseContent;
use App\Helpers\Helper;
use App\CourseContentFile;
use App\Mail\NewStudentInCourse;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;
use Intervention\Image\ImageManagerStatic as Image;

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
		$paypal_id = config('services.paypal.id');

		return view('courses.detail', compact('course', 'related','paypal_id'));
	}
	public function show_payment($id) {
		$course = Course::find($id);		
		return view('courses.payment', compact('course'));
	}

	public function inscribe (Course $course) {
		$course->students()->attach(auth()->user()->student->id);

		\Mail::to($course->teacher->user)->send(new NewStudentInCourse($course, auth()->user()->name));

		return back()->with('message', ['success', __("Inscrito correctamente al curso")]);
	}

	public function subscribed () {
		$courses = Course::whereHas('students', function($query) {
			$query->where('user_id', auth()->id());
		})->get();
		return view('courses.subscribed', compact('courses'));
	}
	public function payed() {
		$courses = Course::whereHas('userPayment', function($query) {
			$query->where('user_id', auth()->id());
		})->get();
		return view('courses.payed', compact('courses'));
	}
	public function create_user_payment_course(Request $request){
		$course_id 	= request('course_id');
		$valor 		= request('valor');
		$user_id 	= auth()->id();
		$payment 	= UserPayment::create([
					'course_id'	=> $course_id,
					'user_id'	=> $user_id,
					'valor' 	=> $valor
				]);
		if($request->session()->has('coupon')){
			$coupon = session('coupon');               
			$coup = Coupon::where('code',$coupon)->first();
			if($coup){
				if($coup->quantity > 0)
				{    
					$coup->quantity--;
					$coup->save();
				}
			}
			$request->session()->forget('coupon');
		}
		return $payment;
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
		
		$free = $course_request['free'] == "on" ? 1:0;
	//	dd($free);
		$picture = Helper::uploadFile('picture', 'courses');
		$course_request->merge(['picture' => $picture]);
		$course_request->merge(['teacher_id' => auth()->user()->teacher->id]);
		$course_request->merge(['status' => Course::PENDING]);
		$course_request->merge(['free' => $free]);
		Course::create($course_request->input());
		return back()->with('message', ['success', __('Curso enviado correctamente, recibirá un correo con cualquier información')]);
	}

	public function edit ($slug) {
		$course = Course::with(['requirements', 'goals'])->withCount(['requirements', 'goals'])
			->whereSlug($slug)->first();
		$btnText = __("Actualizar curso");
		return view('courses.form', compact('course', 'btnText'));
	}
	public function course_details() {
		$course_id = request('course_id');
		$course = Course::find($course_id);
		return $course;
	}
	public function update_ajax() {
		$course_id = request('course_id');
		$course = Course::find($course_id);
		$course->name = request('name');
		$course->description = request('description');
		if(request('free') == 1) {
			$course->free = 1;
			$course->pay = 0;
			$course->value = 0;
		}
		if(request('pay') == 1) {
			$course->pay = 1;
			$course->free = 0;
			$course->value = request('value');
		}
		if(request('suscription') == 1) {
			$course->pay = 0;
			$course->free = 0;
			$course->value = 0;
		}
		$course->save();
		return $course;
	}

	public function update (CourseRequest $course_request, Course $course) {
		if($course_request->hasFile('picture')) {
			\Storage::delete('courses/' . $course->picture);
			$picture = Helper::uploadFile( "picture", 'courses');
			$course_request->merge(['picture' => $picture]);
		}
		$free = $course_request['free'] == "on" ? 1:0;
		$course_request->merge(['free' => $free]);
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
	public function showContent(Course $course,$paginate = null) {		
		try {			
			if(request('paginate') == null){
				$paginate = 5;
			}
			else{
				$paginate = request('paginate');
			}
			$contents = CourseContent::where('course_id',$course->id)->paginate($paginate);
		//	dd($contents);
			return view('courses.content', compact('contents','course'));
		} catch (\Exception $exception) {
			return back()->with('message', ['danger', __("Error al mostrar los datos")]);
		}
	}
	public function showContentFree(Course $course,$paginate = null) {		
		try {			
			if(request('paginate') == null){
				$paginate = 5;
			}
			else{
				$paginate = request('paginate');
			}
			$contents = CourseContent::where('course_id',$course->id)->paginate($paginate);
		//	dd($contents);
			return view('courses.content', compact('contents','course'));
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
	public function showVideoAjax($id) {
		$file = CourseContentFile::find($id);
		return response()->json($file);	
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
		if($request->file('picture') != null)
		{
			$request->validate([
				'picture' => 'mimes:jpeg,png,bmp,gif',
			]);					
			$pic = Helper::uploadFile('picture', 'courses');
			$content->picture = $pic;
		}

		$content->titulo = $request->get('titulo');
		$content->course_id = $request->get('course_id');
		$content->save();
		return back()->with('message', ['success', __("Seccion creada correctamente")]);
	}
	//voy a editar los contenidos
	public function editContent(Course $course){
		$contents = CourseContent::where('course_id',$course->id)->get();
		return view('courses.edit_content',compact('contents'));		

	}
	public function editContentAction(Request $request) {
		try {
			// actualizar la tabla content files

			//dd($request->file);
			$id = $request->id;//request('content_id');
			
			$content = CourseContent::find($id);
			$request->validate([
				'title' => 'required|max:255',			
			]);
			if($request->file('picture') != null)
			{
				$request->validate([
					'picture' => 'mimes:jpeg,png,bmp,gif',
				]);
				if($content->picture != ""){
					\Storage::delete('courses/' . $content->picture);
				}	
				$pic = Helper::uploadFile('picture', 'courses');
				$content->picture = $pic;
			}
			$content->titulo = request('title');
			$content->save();
			return back()->with('message', ['success', __("Seccion actualizada correctamente")]);
		} catch (\Exception $exception) {			
			return back()->with('message', ['danger', __("Error al actualizar los datos ".$exception)]);
		}		

	}
	public function deleteContentAction(Request $request){
		try {
			$id = $request->delete_id;	
			$content = CourseContent::find($id);			
			if($content->picture != ""){
				\Storage::delete('courses/' . $content->picture);
			}
			$content->delete();
			return back()->with('message', ['success', __("Seccion eliminada correctamente")]);
		} catch (\Throwable $th) {
			return back()->with('message', ['danger', __("Error al eliminar los datos ".$th)]);
		}

		

	}	

	public function addContentFile(Request $request) {
		//dd($request);
		//$request->file('video')->isValid();

		//$document = $request->file('archivo');
		$request->validate([
			'description' => 'required'
		]);
		$content_file = new CourseContentFile();
		if($request->file('archivo') != null)
		{
			$request->validate([
				'archivo' => 'mimes:pdf,txt,docx,xlsx,mp4s,m4a,mp4a,mp4',
			]);
			$archivo = Helper::uploadFile('archivo', 'courses');
			$content_file->arhivo = $archivo;
		}

		if(request('url_vimeo') == null && request('url_youtube') == null && $request->file('archivo') == null)
		{
			//return back()->with('message', ['danger', __("Al menos tiene que existir o un video o un archivo")]);
			$content_file->path 	= "";
			$content_file->arhivo 	= "";
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
		$content_file->description = request('description');
		$content_file->save();
		
		return back()->with('message', ['success', __("Datos subidos correctamente")]);
	}
	public function download($file){
		$file_path = public_path('images/courses/'.$file);
		return response()->download($file_path);
	}
	//editar los archivos segun la clase seleccionada
	public function editContentFiles($id){
		$contenFiles = CourseContentFile::where('course_content_id',$id)->get();
		return view('courses.edit_content_files', compact('contenFiles'));
	}
	public function editContentFilesAction(Request $request) {		
		try{
			//dd($request);
			$id = request('course_content_id');
			$request->validate([
				'description' => 'required',
				'titulo_video' => 'required',
			]);
			$content_file = CourseContentFile::find($id);
			if($request->file('archivo') != null)
			{
				$request->validate([
					'archivo' => 'mimes:pdf,txt,docx,xlsx,mp4s,m4a,mp4a,mp4',
				]);
				if($content_file->arhivo != "")
				{
					\Storage::delete('courses/' . $content_file->arhivo);
				}
				$archivo = Helper::uploadFile('archivo', 'courses');
				$content_file->arhivo = $archivo;
			}
	
		/*	if(request('url_vimeo') == null && request('url_youtube') == null && $request->file('archivo') == null)
			{
				return back()->with('message', ['danger', __("Al menos tiene que existir o un video o un archivo")]);
			}*/
			
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
			$content_file->description = request('description');
			$content_file->save();
			
			return back()->with('message', ['success', __("Datos actualizados correctamente")]);
			
		}
		catch(\Throwable $th){
			return back()->with('message', ['danger', __("Problemas en actualizar")]);
		}

	}
	public function deleteContentFile(Request $request) {
		try {
			$content_file = CourseContentFile::find($request->delete_id);
			$content_file->delete();
			return back()->with('message', ['success', __("Datos eliminados correctamente")]);
		} catch (\Throwable $th) {
			return back()->with('message', ['danger', __("Problemas en eliminar")]);
		}
	}
	public function freeCourses() {
		try {
			//aqui voy a mostrar los cursos que soy gratis
			if(auth()->check()){	
				$courses = Course::withCount(['students'])
				->with('category', 'teacher', 'reviews','userPayment')
				->where('status', Course::PUBLISHED)
				->where('free',1)
				->latest()
				->paginate(12);
				
				return view('home', compact('courses'));
				
			}       
			else{
				$courses = Course::withCount(['students'])
				->with('category', 'teacher', 'reviews','userPayment')
				->where('status', Course::PUBLISHED)
				->where('free',1)
				->latest()
				->paginate(12);
	
			return view('home', compact('courses'));
			}
		} catch (\Throwable $th) {
			return back()->with('message', ['danger', __("Uff hubo un problema al mostrar los datos")]);
		}
	}
	public function payCourses() {
		try {
			//aqui voy a mostrar los cursos que soy gratis
			if(auth()->check()){	
				$courses = Course::withCount(['students'])
				->with('category', 'teacher', 'reviews','userPayment')
				->where('status', Course::PUBLISHED)
				->where('pay',1)
				->latest()
				->paginate(12);
				
				return view('home', compact('courses'));
				
			}       
			else{
				$courses = Course::withCount(['students'])
				->with('category', 'teacher', 'reviews','userPayment')
				->where('status', Course::PUBLISHED)
				->where('pay',1)
				->latest()
				->paginate(12);
	
			return view('home', compact('courses'));
			}
		} catch (\Throwable $th) {
			return back()->with('message', ['danger', __("Uff hubo un problema al mostrar los datos")]);
		}
	}
	public function paypalButon(){
		//este metodo es para pagar a paypal ya con descuento si tiene		
		$course_id = request('course_id');
		$course = Course::find($course_id);
		$paypal_id = config('services.paypal.id');
		$amount = $course->value;
		$descuento = 0;
		if(request('coupon')!= ""){
			$coupon = request('coupon');
			
		   //valido el coupon que sea correcto y que tenga existencia
			if($this->checkCoupon($coupon)){
				session(['coupon' => $coupon]); // almaceno el coupon en una session para una vez que se confirme la subscripcion rebajarlo del total
				//ahora aplico el descuento para este coupon
				$price      = $course->value;                    
				$percent    = Coupon::where('code',$coupon)->first()->percent;
				$new_price  = $price - ($price * $percent);		
				$amount 	= $new_price;
				$descuento  = $price * $percent;		
				return view('courses.paypal', compact('amount','paypal_id','course','descuento'));
			}
			else{
				return back()->with('message', ['danger', __("Cupon No Valido")]);
			}
		}
		else {
			return view('courses.paypal', compact('amount','paypal_id','course','descuento'));
		}
	}
	public function checkCoupon($coupon){
        $result = false;
        $coup = Coupon::where('code',$coupon)->first();
        if($coup){
            if($coup->quantity > 0)
            {
                $result = true;  
            }
        }

        return $result;
    }

}
