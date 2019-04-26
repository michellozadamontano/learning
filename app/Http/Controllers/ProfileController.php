<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Rules\StrengthPassword;

class ProfileController extends Controller
{
    public function index () {
    	$user = auth()->user()->load('socialAccount');
    	return view('profile.index', compact('user'));
	}
	public function getUser(Request $request ) {
		$user_id = $request['user_id'];
		$user    = User::with('student')->find($user_id);		
		return $user;
	}
	public function updatePhoto(Request $request) {
		$this->validate($request,[            
			'picture'   => 'required',
			'title' 	=> 'required'
        ]);
		$user = auth()->user();
		$student = $user->student;
        if($request->get('picture'))
        {			
		  $image = $request->get('picture');
		  \Storage::delete('users/' . $user->picture);
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          \Image::make($request->get('picture'))->save(public_path('images/users/').$name);
		}
		$user->picture = $name;
		$user->save();
		$student->title = $request['title'];
		$student->save();
		return $user;
	}

    public function update () {
		$this->validate(request(), [
			'password' => ['confirmed', new StrengthPassword]
		]);

		$user = auth()->user();
		$user->password = bcrypt(request('password'));
		$user->save();
	    return back()->with('message', ['success', __("Usuario actualizado correctamente")]);
	}
	public function update_ajax () {
		$this->validate(request(), [
			'password' => ['confirmed', new StrengthPassword]
		]);

		$user = auth()->user();
		$user->password = bcrypt(request('password'));
		$user->save();
	    return response()->json('Usuario actualizado correctamente');
    }
}
