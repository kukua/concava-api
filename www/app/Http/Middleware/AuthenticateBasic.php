<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
		if (Auth::onceBasic())
		{
			throw new HttpException(401, 'Invalid credentials.');
		}

		return $next($request);
	}
}
