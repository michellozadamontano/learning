<?php
use Illuminate\Http\Request;
//use Telegram\Bot\Api;
//require_once base_path('vendor/irazasyed/telegram-bot-sdk/src/Traits/Telegram.php');

Route::get('/set_language/{lang}', 'Controller@setLanguage')->name( 'set_language');

Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider')->name('social_auth');
Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/', function () {
    return view('welcome');
});

/*Route::post('/bot/getupdates', function() {	
	$updates = Telegram::getUpdates();
    dd($updates);
    return (json_encode($updates));
});

Route::post('bot/sendmessage', function() {
    Telegram::sendMessage([
        'chat_id' => '680403039',
        'text' => 'Hello world!'
    ]);
    return;
});*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/free', 'CourseController@freeCourses')->name('free');
Route::get('/pay', 'CourseController@payCourses')->name('pay');
Route::get('/membresia', 'CourseController@membresiaCourses')->name('membresia');

/*Route::get('courses/{path?}', [
    'uses' => 'HomeController@index',
    'as' => 'courses',
    'where' => ['path' => '.*']
]);*/


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
		Route::get('/payed', 'CourseController@payed')->name('courses.payed');
		Route::get('/epay_acepted','CourseController@epayAcepted');
		Route::get('/epay_rejected','CourseController@epayRejected');
		Route::get('/epay_pending','CourseController@epayPending');
		Route::get('/show_payment/{id}', 'CourseController@show_payment')->name('courses.payment_details');
		Route::post('/payment','CourseController@create_user_payment_course');
		Route::post('/paypal','CourseController@paypalButon')->name('courses.paypal');
		Route::get('/{course}/inscribe', 'CourseController@inscribe')->name('courses.inscribe');
		Route::post('/add_review', 'CourseController@addReview')->name('courses.add_review');
		Route::get('/{course}/content/{paginate?}', 'CourseController@showContent')->name('courses.content');
		Route::get('/{course}/add_content', 'CourseController@addContent')->name('courses.add_content');
		Route::get('/{course}/edit_content', 'CourseController@editContent')->name('courses.edit_content');
		Route::post('/edit_content_action', 'CourseController@editContentAction')->name('courses.editContentAction');
		Route::get('/{id}/edit_content_file', 'CourseController@editContentFiles')->name('courses.editContentFiles');
		Route::post('/edit_content_file_action', 'CourseController@editContentFilesAction')->name('courses.editFileAction');
		Route::post('/deleteContentFile', 'CourseController@deleteContentFile')->name('courses.deleteContentFile');
		Route::post('/delete_content_action', 'CourseController@deleteContentAction')->name('courses.deleteContentAction');
		Route::get('/{id}/show_video', 'CourseController@showVideo')->name('courses.show_video');
		Route::get('/video_ajax/{id}', 'CourseController@showVideoAjax');
		Route::post('/course_details', 'CourseController@course_details');
		Route::post('/update_ajax', 'CourseController@update_ajax');
		Route::post('/add_course_class', 'CourseController@addContentAction')->name('course.add_course_class');
		Route::post('/add_course_class_file', 'CourseController@addContentFile')->name('courses.addFile');
		Route::get('/{file}/download', 'CourseController@download')->name('courses.download');
		Route::group(['middleware' => [sprintf('role:%s', \App\Role::TEACHER)]], function () {
			Route::resource('courses', 'CourseController');
		});
	});

	Route::get('/{course}/free/{paginate?}', 'CourseController@showContentFree')->name('courses.free');
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
		Route::get('/epay', 'SubscriptionController@plan_epay')->name('subscriptions.plan_epay');
		Route::post('/paypal_suscription', 'SubscriptionController@paypalRedirect')->name('subscriptions.paypal_redirect');
		Route::post('/epay_suscription', 'SubscriptionController@epayRedirect')->name('subscriptions.epay_redirect');
        // route for processing payment
        Route::post('/paypal', 'PaymentController@payWithpaypal')
            ->name('subscriptions.paypal');
        // route for check status of the payment
        Route::get('/status', 'PaymentController@getPaymentStatus')
			->name('subscriptions.status');
		// paypal plan subscriptionredirect
	//	Route:get('execute-agreement/{success}', 'PlanController@execute')
	//	->name('subscriptions.agreement');
		Route::post('/plan/create', 'PaypalController@create_plan')->name('subscriptions.paypalplan');
		Route::get('/subscribe/paypal', 'PaypalController@paypalRedirect')->name('subscriptions.redirect');
		Route::get('/subscribe/paypal/return', 'PaypalController@paypalReturn')->name('subscriptions.return');
		Route::get('/subscribe/paypal/cancel', 'PaypalController@pyaplCancel')->name('subscriptions.cancel');
		Route::get('/paypalsubscription','PaypalController@admin')->name('subscriptions.paypal');
		Route::post('/paypal/suspend','PaypalController@paypalSuspend')->name('subscriptions.suspend');
		Route::post('/paypal/cancel','PaypalController@paypalCancel')->name('subscriptions.cancelar');
		Route::post('/paypal/reactivate','PaypalController@paypalReactivate')->name('subscriptions.reactivate');
		Route::get('/paypalplans', 'PaypalController@paypalPlans')->name('subscriptions.paypalPlans');
		Route::get('/users_subscribed','PaypalController@getUserSubscribed');
		Route::post('/users_subscribed_range','PaypalController@getUserSubscribedByRange');
		Route::get('/users_subscribed_count','PaypalController@getSubscriptionStatistics');
		Route::get('/users_registered','PaypalController@getUserRegistered');
		Route::get('/payu/plan','PayuController@payu_plan')->name('subscriptions.payulplan');
		Route::post('/payu/checkout','PayuController@payucheckout')->name('subscriptions.payucheckout');
		Route::get('/payu/apicheckout','PayuController@api_payu_checkout')->name('subscriptions.apicheckout');
	});

	Route::group(['prefix' => "invoices"], function() {
		Route::get('/admin', 'InvoiceController@admin')->name('invoices.admin');
		Route::get('/{invoice}/download', 'InvoiceController@download')->name('invoices.download');
	});
});

Route::group(["prefix" => "profile", "middleware" => ["auth"]], function() {
	Route::get('/', 'ProfileController@index')->name('profile.index');
	Route::put('/', 'ProfileController@update')->name('profile.update');
	Route::post('/change', 'ProfileController@update_ajax');
	Route::post('/getuser', 'ProfileController@getUser');
	Route::post('/updatephoto', 'ProfileController@updatePhoto');
});
Route::group(["prefix" => "coupon", "middleware" => ["auth"]], function() {
	Route::get('/', 'CouponController@index')->name('coupon.index');
	Route::post('/', 'CouponController@store')->name('coupon.store');
	Route::put('/{id}', 'CouponController@update')->name('coupon.update');
	Route::delete('/{id}', 'CouponController@destroy')->name('coupon.delete');
});
Route::group(["prefix" => "category", "middleware" => ["auth"]], function() {
	Route::get('/', 'CategoryController@index');
	Route::post('/', 'CategoryController@store');
	Route::put('/{id}', 'CategoryController@update');
	Route::delete('/{id}', 'CategoryController@destroy');
});

Route::group(['prefix' => "solicitude"], function() {
	Route::post('/teacher', 'SolicitudeController@teacher')->name('solicitude.teacher');
});

Route::group(['prefix' => "teacher", "middleware" => ["auth"]], function() {
	Route::get('/courses', 'TeacherController@courses')->name('teacher.courses');
	Route::get('/students', 'TeacherController@students')->name('teacher.students');	
	Route::post('/send_message_to_student', 'TeacherController@sendMessageToStudent')->name('teacher.send_message_to_student');
	Route::post('/ajax_courses', 'TeacherController@getCourses');
	Route::post('/ajax_students', 'TeacherController@getStudent');
	Route::post('/ajax_sendmessage', 'TeacherController@sendMessage');
});

Route::group(['prefix' => "admin", "middleware" => ['auth', sprintf("role:%s", \App\Role::ADMIN)]], function() {
	Route::get('/courses', 'AdminController@courses')->name('admin.courses');
	Route::get('/paypal', 'AdminController@paypal')->name('admin.paypal');
	Route::get('/teachers', 'AdminController@teachers')->name('admin.teachers');
	Route::get('/students', 'AdminController@student')->name('admin.students');
	Route::get('/student_data', 'AdminController@dataStudent')->name('admin.datastudents');
	Route::get('/courses_json', 'AdminController@coursesJson')->name('admin.courses_json');
	Route::get('/courses_excel', 'AdminController@coursesExcel');
	Route::post('/courses/updateStatus', 'AdminController@updateCourseStatus');
	Route::post('/courses/showCourseContent','AdminController@showCourseContent');
	Route::post('/courses/showContentFiles','AdminController@showContentFiles');

	Route::get('/students', 'AdminController@students')->name('admin.students');
	Route::get('/students_json', 'AdminController@studentsJson')->name('admin.students_json');
	Route::get('/teachers', 'AdminController@teachers')->name('admin.teachers');
	Route::get('/teachers_json', 'AdminController@teachersJson')->name('admin.teachers_json');
	Route::get('/traiding/{value?}', 'AdminController@traiding')->name('admin.traiding');

	Route::get('/payumember', 'AdminController@usersColombia');
	Route::post('/payucreate', 'AdminController@payuColombia');
	Route::post('/payuupdate/{id}', 'AdminController@payuUpdate');
	Route::post('/payudelete', 'AdminController@payuDelete');
});

Route::group(['prefix' => "traiding", "middleware" => ["auth"]], function() {
	Route::get('/quote/{symbol}', 'IexController@stockQuote');	
});
Route::group(['prefix' => "payment", "middleware" => ["auth"]], function() {
	Route::get('/', 'UserPaymentController@index');	
	Route::post('/create', 'UserPaymentController@create_ajax');
	Route::post('/update/{id}', 'UserPaymentController@update_ajax');
	Route::post('/delete/{id}', 'UserPaymentController@delete_ajax');
	Route::get('/users', 'UserPaymentController@getUser');
	Route::get('/courses', 'UserPaymentController@getPayCourse');
});
Route::group(["prefix" => "epay", "middleware" => ["auth"]], function() {
	Route::get('/', 'EpayController@index');	
	Route::post('/create', 'EpayController@create_plan');
	Route::post('/update', 'EpayController@update_plan');
	Route::post('/epay_data', 'EpayController@epay_data')->name('epay.epay_data');
	Route::post('/epay_suscription', 'EpayController@epay_suscription')->name('epay.epay_suscription');
});