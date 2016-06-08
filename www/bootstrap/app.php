<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
	(new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
	// Do nothing
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(realpath(__DIR__.'/../'));

$app->configure('app');
$app->configure('database');
$app->configure('cache');
$app->configure('user');
$app->configure('entrust');

$app->withFacades();
$app->withEloquent();

foreach ((array) config('app.aliases') as $alias => $class) {
	class_alias($class, $alias);
}

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
	Illuminate\Contracts\Debug\ExceptionHandler::class,
	App\Exceptions\Handler::class
);

$app->singleton(
	Illuminate\Contracts\Console\Kernel::class,
	App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->routeMiddleware([
	'throttle' => GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware::class,
	'cross-origin' => App\Http\Middleware\CrossOriginHeaders::class,
	'auth.admin' => App\Http\Middleware\AuthenticateAdmin::class,
	'auth.token' => App\Http\Middleware\AuthenticateToken::class,
	'auth.basic' => App\Http\Middleware\AuthenticateBasic::class,

	'role' => \Zizaco\Entrust\Middleware\EntrustRole::class,
	'permission' => \Zizaco\Entrust\Middleware\EntrustPermission::class,
	'ability' => \Zizaco\Entrust\Middleware\EntrustAbility::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

foreach ((array) config('app.providers') as $class) {
	$app->register($class);
}

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group([
	'namespace' => 'App\Http\Controllers',
	'middleware' => ['throttle:60,1', 'cross-origin'],
	'prefix' => 'v1',
], function ($app) {
	require __DIR__.'/../app/Http/routes.php';
});

return $app;
