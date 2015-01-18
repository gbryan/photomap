<?php

trait TestMarkersTrait {
	
	protected $testMarker = [
		'name'	=> 'City Hall',
		'loc'	=> [
			'type'			=> 'Point',
			'coordinates'	=> [-122.273257, 37.869230]
		],
		'description'	=> 'My fancy description',
		'tags'	=> ['building', 'government']
	];

	protected $boundingBoxCoordinates = [
		[-122.276025, 37.867841],
		[-122.270628, 37.871830]
	];
}
