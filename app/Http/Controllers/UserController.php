<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \App\Models\User;

class UserController extends Controller
{
    public function create(Request $request) 
    {
		$user = new User;
		$user->username = $request->username;
		$user->password = app('hash')->make('$request->password');
		$user->save();
        return response(null, 201);
    }
}
