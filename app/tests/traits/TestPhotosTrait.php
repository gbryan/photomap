<?php

trait TestPhotosTrait {
	
	public function createTestPhoto(array $attributes, $filename = 'test_photo.jpg')
	{
		$uploadedPhoto = new Symfony\Component\HttpFoundation\File\UploadedFile(__DIR__ . '/../data/' . $filename, $filename);

		return $this->sendPost('photos', $attributes, ['photo' => $uploadedPhoto]);
	}
}
