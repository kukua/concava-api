<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthenticateBasic
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
		return Auth::onceBasic() ?: $next($request);
	}
}
