<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthenticateAdmin
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
		if ( ! Auth::user()->is_admin)
		{
			return response('Unauthorized.', 401);
		}

		return $next($request);
	}
}
