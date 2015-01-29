<?php

class MarkersApiTest extends ApiTester {

	use TestPhotosTrait;
	use TestMarkersTrait;

	protected $usesDb = true;

	public function test_creating_a_marker_returns_success_message()
	{
		$response = $this->sendPost('markers', $this->testMarker);
		$this->assertResponseOk();
		$responseData = $response->getData();
		$this->assertEquals('success', $responseData->status);
	}

	public function test_creating_marker_without_geometry_returns_error_message()
	{
		$data = $this->testMarker;
		unset($data['geometry']);

		$response = $this->sendPost('markers', $data);
		$this->assertResponseStatus(400);
		$responseData = $response->getData();
		$this->assertContains('The geometry field is required.', $responseData->errors->geometry);
	}

	public function test_requesting_a_single_marker_returns_success_message()
	{
		$response = $this->sendPost('markers', $this->testMarker);
		$this->assertResponseStatus(200);
		$id = $response->getData()->data->_id;

		$response = $this->sendGet('markers/' . $id);
		$this->assertSuccessResponse($response, 'Invalid response when getting individual marker');
	}

	public function test_requesting_marker_with_photos_returns_array_of_photos_with_marker()
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
		$actualPhotos = $response->getData()->data->photos;

		foreach ($actualPhotos as $actualPhoto)
		{
			$this->assertContains($actualPhoto->_id, $photoIds, 'Marker does not show the expected photo IDs.');
		}
	}

	public function test_requesting_markers_with_bounding_box()
	{
		$this->login();

		$inData1 = $this->testMarker;
		$inData1['name'] = 'In bounds 1';
		$inBounds1 = Marker::create($inData1);
		
		$inData2 = $this->testMarker;
		$inData2['name'] = 'In bounds 2';
		$inData2['geometry']['coordinates'] = [-122.273258, 37.869231];
		$inBounds2 = Marker::create($inData2);

		$outData = $this->testMarker;
		$outData['name'] = 'Out of bounds';
		$outData['geometry']['coordinates'] = [-124.273258, 39.869231];
		$outOfBounds = Marker::create($outData);

		$response = $this->sendGet('markers', ['format' => 'default', 'operator' => 'within', 'shape' => 'box', 'coordinates' => json_encode($this->boundingBoxCoordinates)]);
		$this->assertResponseStatus(200);

		$markers = $response->getData()->data->data;
		$this->assertEquals(2, count($markers));

		foreach ($markers as $marker) {

			$actualNames[] = $marker->name;
		}

		$this->assertContains($inData1['name'], $actualNames);
		$this->assertContains($inData2['name'], $actualNames);
		$this->assertNotContains($outData['name'], $actualNames);
	}

	public function test_requesting_markers_returns_feature_collection_when_requesting_geojson()
	{
		$this->login();
		
		$marker = Marker::create($this->testMarker);

		$response = $this->sendGet('markers', ['format' => 'geojson', 'operator' => 'within', 'shape' => 'box', 'coordinates' => json_encode($this->boundingBoxCoordinates)]);
		$this->assertResponseStatus(200);
		$data = $response->getData()->data->data;
		$this->assertEquals('FeatureCollection', $data->type);
	}

}
