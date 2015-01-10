<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		$app = require __DIR__.'/../../bootstrap/start.php';

		$app['path.storage'] = storage_path() . '/test/';

		return $app;
	}

}
