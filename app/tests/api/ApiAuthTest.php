<?php

class ApiAuthTest extends ApiTester {
	
	public function test_creating_a_marker_without_logging_in_returns_unauthorized()
	{
		$data = $this->testMarker;
		$response = $this->sendPost('markers', $data, [], false);
		$this->assertResponseStatus(401);
	}

	// Tests in other classes ensure that access is granted with valid credentials.
}
