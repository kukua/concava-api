<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use HttpException;

class AuthenticateAdmin {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	function handle ($request, Closure $next) {
		if ( ! Auth::user()->is_active) {
			throw new HttpException(401, 'User account disabled.');
		}
		if ( ! Auth::user()->is_admin) {
			throw new HttpException(401, 'Unauthorized.');
		}

		return $next($request);
	}
}
