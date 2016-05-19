<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserToken;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
		try
		{
			$model = $response = parent::store();
		}
		catch (QueryException $e)
		{
			throw new HttpException(400, $e->getPrevious()->getMessage(),
				$e, $headers = [], $e->getCode());
		}

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
