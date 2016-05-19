<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Zend\Diactoros\Response as ZendResponse;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		AuthorizationException::class,
		HttpException::class,
		ModelNotFoundException::class,
		ValidationException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	function report (Exception $e)
	{
		parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	function render ($request, Exception $e)
	{
		$statusCode = 500;
		$code = (int) $e->getCode();
		$message = (string) $e->getMessage();

		if ($e instanceof HttpException)
		{
			$statusCode = $e->getStatusCode();
		}

		if (empty($message))
		{
			$res = new ZendResponse('php://memory', $statusCode);
			$message = $res->getReasonPhrase() . '.';
		}

		$error = [
			'status'  => & $statusCode,
			'code'    => & $code,
			'message' => & $message
		];

		if (\Config::get('app.debug'))
		{
			$error['meta'] = [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString()
			];
		}

		return response()->json($error, $statusCode);
	}
}
