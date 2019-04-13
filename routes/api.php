<?php

use Illuminate\Http\Request;
use Telegram\Bot\Api;

/*Route::post('/bot/getupdates', function() {
    $telegram = new Api('886157042:AAHF5eycxJHX_K-tDnPEn17bAvoQhqErLAg');
    $response = $telegram->getMe();
    $response = $telegram->getUpdates();   
    

  //  $updates = Telegram::getUpdates();
    
    return (json_encode($response));
});*/

/*Route::post('bot/sendmessage', function() {
   
    $telegram = new Api('886157042:AAHF5eycxJHX_K-tDnPEn17bAvoQhqErLAg');

    $response = $telegram->sendMessage([
    'chat_id' => '680403039', 
    'text' => 'Hello World'
    ]);

    $messageId = $response->getMessageId();
      //  return;
});*/
Route::post('bot/sendmessage', 'TelegramController@getUpdate');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
