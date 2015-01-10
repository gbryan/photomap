<?php

class MarkersApiTest extends ApiTester {

	protected $usesDb = true;

	public function test_creating_a_marker_returns_success_message()
	{
		$response = $this->sendPost('markers', $this->testMarker);
		$this->assertResponseOk();
		$responseData = $response->getData();
		$this->assertEquals('success', $responseData->status);
	}

	public function test_creating_marker_without_loc_returns_error_message()
	{
		$data = $this->testMarker;
		unset($data['loc']);

		$response = $this->sendPost('markers', $data);
		$this->assertResponseStatus(400);
		$responseData = $response->getData();
		$this->assertContains('The loc field is required.', $responseData->errors->loc);
	}

	public function test_requesting_a_single_marker_returns_success_message()
	{
		$response = $this->sendPost('markers', $this->testMarker);
		$this->assertResponseStatus(200);
		$id = $response->getData()->data->_id;

		$response = $this->sendGet('markers/' . $id);
		$this->assertSuccessResponse($response, 'Invalid response when getting individual marker');
	}

}
