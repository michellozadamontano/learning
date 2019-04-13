<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;

class TelegramController extends Controller
{
    public function getUpdate(Request $request) {        

        $telegram = new Api('886157042:AAHF5eycxJHX_K-tDnPEn17bAvoQhqErLAg');       
        //$response = $telegram->getUpdates(); 
       $chat_id = $request['message']['chat']['id'];
       $text = $request['message']['text'];
        $name =  $request['message']['from']['first_name'];
        //'680403039'
        if($text == "/mi@michellm_bot"){
            $response = $telegram->sendMessage([
                'chat_id' => $chat_id, 
                'text' => 'Hola soy el Robot '. $name 
                ]);
        }
        
        
        $messageId = $response->getMessageId();
    }
}
