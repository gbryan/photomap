<?php

class MarkersTest extends BaseTester {

	protected $usesDb = true;

	public function test_create_a_marker_and_ensure_output_matches_input()
	{
		$marker = Marker::create($this->testMarker);

		$fields = array_keys($this->testMarker);
		$this->assertEquals($this->testMarker, $marker->onlyFields($fields));
	}

	public function test_creating_marker_fails_when_loc_is_not_provided()
	{
		$data = $this->testMarker;
		unset($data['loc']);

		$marker = new Marker($data);

		$this->assertFalse($marker->save());
	}

}
