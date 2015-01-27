app.controller('LoginController', ['$scope', 'LoginFactory', function($scope, LoginFactory) {

	$scope.login = function(credentials) {

		LoginFactory.login({email: credentials.email, password: credentials.password});
	}
}]);
