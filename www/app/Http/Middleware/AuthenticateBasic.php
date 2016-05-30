<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use HttpException;

class AuthenticateBasic {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	function handle ($request, Closure $next) {
		if (Auth::onceBasic()) {
			throw new HttpException(401, 'Invalid credentials.');
		}
		if ( ! Auth::user()->is_active) {
			throw new HttpException(401, 'User account disabled.');
		}

		return $next($request);
	}
}
