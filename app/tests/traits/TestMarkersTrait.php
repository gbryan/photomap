<?php

trait TestMarkersTrait {
	
	protected $testMarker = [
		'type'			=> 'Feature',
		'name'			=> 'City Hall',
		'description'	=> 'My fancy description',
		'tags'			=> ['building', 'government'],
		'geometry'		=> [
			'type'			=> 'Point',
			'coordinates'	=> [-122.273257, 37.869230]
		]
	];

	protected $boundingBoxCoordinates = [
		[-122.276025, 37.867841],
		[-122.270628, 37.871830]
	];
}
