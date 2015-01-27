app.controller('MapController', ['$scope', 'MarkerFactory', function($scope, MarkerFactory) {

	$scope.infoWindow = new google.maps.InfoWindow();

	// @todo Error handling!!
	MarkerFactory.getMarkers({
		format: "geojson",
		operator: "within", 
		shape: "box",
		// Bottom-left, top-right
		coordinates: "[[-122.294320, 37.836333], [-122.252778, 37.902869]]"
	}).success(function(paginator) {
		
		var markers = paginator.data.data;
		$scope.map.data.addGeoJson(markers);
		setupInfoWindows();
	});

	// @todo When clicking on marker, load known data immediately and shown spinner while loading additional data (photos, etc.).
	// @todo This may not belong in the controller...
	// @todo Error handling!
	function setupInfoWindows() {

		$scope.map.data.addListener('click', function(event) {
	console.dir(event.feature);

			var id = event.feature.getProperty('_id');

			MarkerFactory.getMarker( id, { include_photos: true, format: "geojson" })
				.success(function(data) {

	console.dir(data);
					var photos = data.data.properties.photos;

					var photoLinks = '';
					var images = '';

					for (var i = 0; i < photos.length; i++) {

						photoLinks += '<a href="' + photos[i].url + '" target="_blank">' + photos[i].title + '</a><br />';
						images += 
							'<div class="marker-photo-container">' + 
								'<a href="' + photos[i].url + '" target="_blank">' + 
									'<img class="marker-photo" src="' + photos[i].url + '">' + 
								'</a>' +
								'<div class="photo-title">' +
									photos[i].title +
								'</div>' +
							'</div>';
					}

					//show an infowindow on click   
					$scope.infoWindow.setContent(
						'<div class="info-window">' + 
							'<h1>' + event.feature.getProperty('name') + '</h1>' +
							'<h2>' + event.feature.getProperty('description') + '</h2>' +
							images +
						'</div>'
					);

					var anchor = new google.maps.MVCObject();
					anchor.set("position",event.latLng);
					$scope.infoWindow.open($scope.map,anchor);

				});
		});
	}

}]);
