app.factory('LoginFactory', ['$http', function($http) {

    var login = {};

    login.login = function (credentials) {
        return $http.post('/login', credentials);
    };

    return login;
}]);
