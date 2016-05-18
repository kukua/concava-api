<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Auth;

class UserController extends Controller
{
	protected $class = User::class;

	protected function registerMiddleware ()
	{
		$this->middleware('auth.token', ['except' => 'login']);
		$this->middleware('auth.basic', ['only' => 'login']);
	}

	function login ()
	{
		$user = Auth::user();

		return response()->json($user->toArray() + [
			'token' => $user->tokens()->first()->token
		]);
	}
}
