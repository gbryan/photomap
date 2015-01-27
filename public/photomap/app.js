var app = angular.module('photomap', ['ngRoute', 'ngMap', 'ui.bootstrap', 'angularFileUpload']);

app.config(['$routeProvider',
	function($routeProvider) {
		$routeProvider.
		when('/login', {
			templateUrl: 'photomap/components/login/login.html',
			controller: 'LoginController'
		}).
		when('/map', {
			templateUrl: 'photomap/components/map/map.html',
			controller: 'MapController'
		}).
		when('/upload', {
			templateUrl: 'photomap/components/upload/upload.html',
			controller: 'UploadController'
		}).
		otherwise({
			redirectTo: '/login'
		});
	}
]);
