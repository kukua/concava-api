<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserToken;
use Auth;
use Illuminate\Database\Eloquent\Model;

class UserController extends Controller
{
	protected $class = User::class;

	protected function registerMiddleware ()
	{
		$this->middleware('auth.token', ['except' => ['login', 'store']]);
		$this->middleware('auth.basic', ['only' => 'login']);
		$this->middleware('auth.admin', ['except' => ['login', 'store']]);
	}

	function store ()
	{
		$model = $response = parent::store();

		if ( ! ($model instanceof Model))
			return $response;

		$token = UserToken::randomToken();

		UserToken::create([
			'user_id' => $model->id,
			'token' => $token
		]);

		$model->token = $token;

		return $model;
	}

	function login ()
	{
		$user = Auth::user();

		return response()->json($user->toArray() + [
			'token' => $user->tokens()->first()->token
		]);
	}
}
