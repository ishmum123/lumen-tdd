<?php

namespace App\Http\Controllers;

use Validator;
use \Illuminate\Http\Request;
use \App\Models\User;

class UserController extends Controller
{
    public function create(Request $request) 
    { 
        if ($request->all() == null) 
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Invalid JSON Structure'
                ]
            ], 422);

		$validation = $this->validateRequest($request);

		if ($validation != null) return $validation;
	
		$user = new User;

		$user->username = $request->username;
		$user->email = $request->email;
		$user->password = app('hash')->make($request->password);
		$user->save();

        return response(null, 201);
    }

	private function validateRequest(Request $request) 
	{
		$validator = Validator::make($request->all(), [
            'username' => 'required|min:6|unique:users',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/[a-z]/',      
                'regex:/[A-Z]/',      
                'regex:/[0-9]/',                  
            ],
        ]); 

        return $validator->fails() ? response($validator->errors(), 400) : null;
	}
}
