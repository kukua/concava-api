<?php

class TestCase extends Laravel\Lumen\Testing\TestCase
{
	/**
	 * Creates the application.
	 *
	 * @return \Laravel\Lumen\Application
	 */
	function createApplication()
	{
		return require __DIR__.'/../bootstrap/app.php';
	}
}
