<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Dependencies -->
		<script src="http://maps.google.com/maps/api/js"></script>
		<script src="bower_resources/angular/angular.min.js"></script>
		<script src="bower_resources/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
		<script src="bower_resources/ng-file-upload/angular-file-upload-shim.min.js"></script>
		<script src="bower_resources/ng-file-upload/angular-file-upload.min.js"></script>
		<script src="bower_resources/angular-route/angular-route.min.js"></script>
		<script src="bower_resources/ngmap/build/scripts/ng-map.min.js"></script>

		<link rel="stylesheet" type="text/css" href="bower_resources/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="photomap/components/upload/ng-file-upload.css">
		<link rel="stylesheet" type="text/css" href="photomap/styles.css">

		<!-- Angular app -->
		<script src="photomap/app.js"></script>
		<script src="photomap/components/login/loginController.js"></script>
		<script src="photomap/components/login/loginFactory.js"></script>
		<script src="photomap/components/upload/uploadController.js"></script>
		<script src="photomap/components/map/mapController.js"></script>
		<script src="photomap/components/marker/markerFactory.js"></script>
	</head>

	<body ng-app="photomap">
		<div ng-view id="mainContainer" class="container-fluid"></div>
	</body>
</html>
