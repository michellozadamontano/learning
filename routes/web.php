<?php

Route::get('/set_language/{lang}', 'Controller@setLanguage')->name( 'set_language');

Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider')->name('social_auth');
Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/images/{path}/{attachment}', function($path, $attachment) {
	//$file = sprintf('storage/%s/%s', $path, $attachment);
	
	//return Image::make($file)->response();
	
	/*if(File::exists($file)) {
		dd("estoy entrando aqui");
		return Image::make($file)->response();
	}*/
});

Route::group(['prefix' => 'courses'], function () {

	Route::group(['middleware' => ['auth']], function() {
		Route::get('/subscribed', 'CourseController@subscribed')->name('courses.subscribed');
		Route::get('/{course}/inscribe', 'CourseController@inscribe')->name('courses.inscribe');
		Route::post('/add_review', 'CourseController@addReview')->name('courses.add_review');
		Route::get('/{course}/content', 'CourseController@showContent')->name('courses.content');
		Route::get('/{course}/add_content', 'CourseController@addContent')->name('courses.add_content');
		Route::get('/{id}/show_video', 'CourseController@showVideo')->name('courses.show_video');
		Route::post('/add_course_class', 'CourseController@addContentAction')->name('course.add_course_class');
		Route::post('/add_course_class_file', 'CourseController@addContentFile')->name('courses.addFile');
		Route::get('/{file}/download', 'CourseController@download')->name('courses.download');
		Route::group(['middleware' => [sprintf('role:%s', \App\Role::TEACHER)]], function () {
			Route::resource('courses', 'CourseController');
		});
	});

	Route::get('/{course}', 'CourseController@show')->name('courses.detail');
});
/*Route::post('plan/create', 'PaypalController@create_plan')->name('subscriptions.paypalplan');
Route::get('/subscribe/paypal', 'PaypalController@paypalRedirect')->name('subscriptions.redirect');
Route::get('/subscribe/paypal/return', 'PaypalController@paypalReturn')->name('subscriptions.return');*/

Route::group(['middleware' => ['auth']], function () {
	Route::group(["prefix" => "subscriptions"], function() {
		Route::get('/plans', 'SubscriptionController@plans')
		     ->name('subscriptions.plans');
		Route::get('/admin', 'SubscriptionController@admin')
		     ->name('subscriptions.admin');
		Route::post('/process_subscription', 'SubscriptionController@processSubscription')
		     ->name('subscriptions.process_subscription');
		Route::post('/resume', 'SubscriptionController@resume')->name('subscriptions.resume');
		Route::post('/cancel', 'SubscriptionController@cancel')->name('subscriptions.cancel');
        // route for processing payment
        Route::post('/paypal', 'PaymentController@payWithpaypal')
            ->name('subscriptions.paypal');
        // route for check status of the payment
        Route::get('/status', 'PaymentController@getPaymentStatus')
			->name('subscriptions.status');
		// paypal plan subscriptionredirect
	//	Route:get('execute-agreement/{success}', 'PlanController@execute')
	//	->name('subscriptions.agreement');
		Route::get('plan/create', 'PaypalController@create_plan')->name('subscriptions.paypalplan');
		Route::get('/subscribe/paypal', 'PaypalController@paypalRedirect')->name('subscriptions.redirect');
		Route::get('/subscribe/paypal/return', 'PaypalController@paypalReturn')->name('subscriptions.return');
		Route::get('/subscribe/paypal/cancel', 'PaypalController@pyaplCancel')->name('subscriptions.cancel');
		Route::get('/paypalsubscription','PaypalController@admin')->name('subscriptions.paypal');
		Route::post('/paypal/suspend','PaypalController@paypalSuspend')->name('subscriptions.suspend');
		Route::post('/paypal/cancel','PaypalController@paypalCancel')->name('subscriptions.cancelar');
		Route::post('/paypal/reactivate','PaypalController@paypalReactivate')->name('subscriptions.reactivate');
	});

	Route::group(['prefix' => "invoices"], function() {
		Route::get('/admin', 'InvoiceController@admin')->name('invoices.admin');
		Route::get('/{invoice}/download', 'InvoiceController@download')->name('invoices.download');
	});
});

Route::group(["prefix" => "profile", "middleware" => ["auth"]], function() {
	Route::get('/', 'ProfileController@index')->name('profile.index');
	Route::put('/', 'ProfileController@update')->name('profile.update');
});

Route::group(['prefix' => "solicitude"], function() {
	Route::post('/teacher', 'SolicitudeController@teacher')->name('solicitude.teacher');
});

Route::group(['prefix' => "teacher", "middleware" => ["auth"]], function() {
	Route::get('/courses', 'TeacherController@courses')->name('teacher.courses');
	Route::get('/students', 'TeacherController@students')->name('teacher.students');	
	Route::post('/send_message_to_student', 'TeacherController@sendMessageToStudent')->name('teacher.send_message_to_student');
});

Route::group(['prefix' => "admin", "middleware" => ['auth', sprintf("role:%s", \App\Role::ADMIN)]], function() {
	Route::get('/courses', 'AdminController@courses')->name('admin.courses');
	Route::get('/teachers', 'AdminController@teachers')->name('admin.teachers');
	Route::get('/students', 'AdminController@student')->name('admin.students');
	Route::get('/student_data', 'AdminController@dataStudent')->name('admin.datastudents');
	Route::get('/courses_json', 'AdminController@coursesJson')->name('admin.courses_json');
	Route::post('/courses/updateStatus', 'AdminController@updateCourseStatus');
	Route::post('/courses/showCourseContent','AdminController@showCourseContent');
	Route::post('/courses/showContentFiles','AdminController@showContentFiles');

	Route::get('/students', 'AdminController@students')->name('admin.students');
	Route::get('/students_json', 'AdminController@studentsJson')->name('admin.students_json');
	Route::get('/teachers', 'AdminController@teachers')->name('admin.teachers');
	Route::get('/teachers_json', 'AdminController@teachersJson')->name('admin.teachers_json');
});