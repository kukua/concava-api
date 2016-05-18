<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Auth;

class UserController extends Controller
{
	protected $class = User::class;

	protected function registerMiddleware ()
	{
		$this->middleware('auth.token', ['except' => ['login', 'store']]);
		$this->middleware('auth.basic', ['only' => 'login']);
		$this->middleware('auth.admin', ['except' => ['login', 'store']]);
	}

	// TODO(mauvm): Add UserToken for newly created User.

	function login ()
	{
		$user = Auth::user();

		return response()->json($user->toArray() + [
			'token' => $user->tokens()->first()->token
		]);
	}
}
