<?php

class PhotosApiTest extends ApiTester {

	protected $usesDb = true;

	protected $testPhoto = [
		'title'			=> 'My fake photo',
		'description'	=> 'Some longish description',
		'date_taken'	=> 1419228568239
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

	public function test_requesting_an_image_on_disk_with_bogus_id_fails()
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

	protected function createTestPhoto(array $attributes, $filename = 'test_photo.jpg')
	{
		$uploadedPhoto = new Symfony\Component\HttpFoundation\File\UploadedFile(__DIR__ . '/../data/' . $filename, $filename);

		return $this->sendPost('photos', $attributes, ['photo' => $uploadedPhoto]);
	}

}
