<?php

class ApiTester extends BaseTester {

	public function setUp()
	{
		parent::setUp();

		Route::enableFilters();
	}

	public function sendPost($endpoint, array $parameters, $files = array(), $login = true)
	{
		$server = array();

		if ($login)
		{
			$server = ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'password'];
		}

		return $this->call('POST', '/api/v1.0/' . $endpoint, $parameters, $files, $server);
	}

	public function sendPut($endpoint, array $parameters, $login = true)
	{
		$server = array();

		if ($login)
		{
			$server = ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'password'];
		}

		return $this->call('PUT', '/api/v1.0/' . $endpoint, $parameters, [], $server);
	}

	public function sendGet($endpoint, $parameters = array(), $login = true)
	{
		$server = array();

		if ($login)
		{
			$server = ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'password'];
		}

		return $this->call('GET', '/api/v1.0/' . $endpoint, $parameters, [], $server);
	}

	public function assertSuccessResponse($response, $message = 'Response error')
	{
		$this->assertResponseOk();
		$this->assertTrue(method_exists($response, 'getData'), $message);
		$responseData = $response->getData();
		$this->assertEquals('success', $responseData->status);
	}
}
