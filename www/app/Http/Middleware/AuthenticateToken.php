<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticateToken
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	function handle ($request, Closure $next)
	{
		$auth = (string) $_SERVER['HTTP_AUTHORIZATION'];

		if ( ! starts_with($auth, 'Token '))
		{
			return response('Authorization Token required.', 401);
		}

		$token = substr($auth, 6);
		$user = User::findByToken($token);

		if ( ! $user)
		{
			return response('Unauthorized.', 401);
		}

		Auth::login($user);

		return $next($request);
	}
}
