<?php

class MarkersApiTest extends ApiTester {

	use TestPhotosTrait;

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

	public function test_requesting_marker_with_photos_returns_array_of_photo_ids_with_marker()
	{
		// Create a test marker.
		$response = $this->sendPost('markers', $this->testMarker);
		$this->assertResponseStatus(200);
		$markerId = $response->getData()->data->_id;

		// Create 2 test photos.
		$photoIds = array();

		foreach (['first', 'second'] as $title)
		{
			$response = $this->createTestPhoto(['title' => $title]);
			$this->assertResponseStatus(200);
			$id = $response->getData()->data->_id;
			$response = $this->sendPost('photos/' . $id, ['marker_id' => $markerId]);
			$this->assertResponseStatus(200);
			$photoIds[] = $id;
		}

		// Request the marker and make sure it has both photos associated with it.
		$response = $this->sendGet('markers/' . $markerId, ['include_photos' => true]);
		$this->assertResponseStatus(200);
		$this->assertObjectHasAttribute('photos', $response->getData()->data, 'No "photos" property on data object');
		$actualPhotoIds = $response->getData()->data->photos;
		$this->assertEquals($photoIds, $actualPhotoIds, 'Marker does not show the expected photo IDs.');
	}

}
