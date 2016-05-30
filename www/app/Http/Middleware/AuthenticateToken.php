<?php

namespace App\Http\Middleware;

use Closure;
use HttpException;
use App\Models\User;
use Auth;

class AuthenticateToken {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	function handle ($request, Closure $next) {
		if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
			throw new HttpException(401, 'Authorization header required.');
		}

		$auth = (string) $_SERVER['HTTP_AUTHORIZATION'];

		if ( ! starts_with(strtolower($auth), 'token ')) {
			throw new HttpException(401, 'Authorization token required.');
		}

		$token = substr($auth, 6);
		$user = User::findByToken($token);

		if ( ! $user) {
			throw new HttpException(401, 'Unauthorized.');
		}
		if ( ! $user->is_active) {
			throw new HttpException(401, 'User account disabled.');
		}

		Auth::login($user);

		return $next($request);
	}
}
