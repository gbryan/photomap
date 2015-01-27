<?php

class PhotosApiTest extends ApiTester {

	use TestPhotosTrait;
	use TestMarkersTrait;

	protected $usesDb = true;

	protected $testPhoto = [
		'title'			=> 'My fake photo',
		'description'	=> 'Some longish description',
	];

	public function test_creating_a_photo_returns_success_message()
	{
		$response = $this->createTestPhoto($this->testPhoto);
		$this->assertSuccessResponse($response);
	}

	public function test_creating_photo_without_photo_data_returns_error_message()
	{
		$response = $this->sendPost('photos', $this->testPhoto, []);
		$this->assertResponseStatus(400);
		$responseData = $response->getData();
		$this->assertContains('The photo field is required.', $responseData->errors->photo);
	}

	public function test_requesting_a_single_photo_displays_photo_information()
	{
		$response = $this->createTestPhoto($this->testPhoto);
		$this->assertResponseStatus(200);
		$id = $response->getData()->data->_id;

		$response = $this->sendGet('photos/' . $id);
		$this->assertSuccessResponse($response, 'Invalid response when getting individual photo');
		$this->assertEquals($id, $response->getData()->data->_id);
	}

	public function test_requesting_an_image_on_disk_displays_requested_photo()
	{
		$response = $this->createTestPhoto($this->testPhoto);
		$this->assertResponseStatus(200);
		$id = $response->getData()->data->_id;

		$response = $this->sendGet('files/' . $id);
		$this->assertResponseStatus(200, 'Response was not 200 when trying to view photo');
	}

	public function test_requesting_an_image_on_disk_with_bogus_id_fails_with_exception()
	{
		$this->setExpectedException('Illuminate\Database\Eloquent\ModelNotFoundException');
		$response = $this->sendGet('files/bogus');
	}

	public function test_updating_a_photos_marker_id_returns_success_message()
	{
		$response = $this->createTestPhoto($this->testPhoto);
		$this->assertResponseStatus(200);
		$id = $response->getData()->data->_id;

		$newData = $this->testPhoto;
		$newData['marker_id'] = 'newly_added';

		$response = $this->sendPost('photos/' . $id, $newData);
		$this->assertSuccessResponse($response, 'Bad response when updating a photo');
		$this->assertEquals('newly_added', $response->getData()->data->marker_id, 'Marker ID was not updated properly!');
	}

	public function test_accessing_no_marker_route_returns_photos_with_no_associated_marker()
	{
		// Create two photos without a marker and one with a marker.
		$response = $this->createTestPhoto($this->testPhoto);
		$this->assertResponseStatus(200);
		$noMarker1 = $response->getData()->data->_id;

		$response = $this->createTestPhoto($this->testPhoto);
		$this->assertResponseStatus(200);
		$noMarker2 = $response->getData()->data->_id;

		$marker = Marker::create($this->testMarker);

		$newData = $this->testPhoto;
		$newData['marker_id'] = $marker->_id;
		$response = $this->createTestPhoto($newData);
		$this->assertResponseStatus(200);
		$withMarker = $response->getData()->data->_id;

		// Make sure that the photos/no-marker route returns the two photos without markers.
		$response = $this->sendGet('photos/no-marker');
		$this->assertResponseStatus(200);

		$photos = $response->getData()->data->data;

		$this->assertEquals(2, count($photos));

		foreach ($photos as $photo)
		{
			$this->assertContains($photo->_id, [$noMarker1, $noMarker2]);
		}
	}

	public function test_creating_a_photo_with_create_marker_parameter_creates_a_marker_with_the_coordinates_of_the_given_photo()
	{
		$data = $this->testPhoto;
		$data['create_marker'] = true;
		$response = $this->createTestPhoto($data);
		$this->assertResponseStatus(200);
		$markerId = $response->getData()->data->marker_id;

		$marker = DB::table('markers')->find($markerId);
		$this->assertNotNull($marker);

		$coordinates = $marker['geometry']['coordinates'];
		$longitude = (string) $coordinates[0];
		$longitude = substr($longitude, 0, 9);
		$this->assertEquals($this->TEST_LONGITUDE, $longitude);
	}

	public function test_using_create_marker_with_a_photo_that_has_no_coordinates_returns_an_error_as_json()
	{
		$data = $this->testPhoto;
		$data['create_marker'] = true;
		$response = $this->createTestPhoto($data, 'no_coordinates.jpg');
		$this->assertResponseStatus(400);

		$this->assertEquals('Marker could not be created because the provided photo has no GPS data.', $response->getData()->message);
	}

}
