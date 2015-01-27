app.factory('MarkerFactory', ['$http', function($http) {

    var urlBase = '/api/v1.0/markers';
    var markers = {};

    markers.getMarkers = function (data) {
        return $http.get(urlBase, { params: data });
    };

    markers.getMarker = function (id, data) {
        return $http.get(urlBase + '/' + id, { params: data });
    };

    // markers.insertMarker = function (marker) {
    //     return $http.post(urlBase, marker);
    // };

    // markers.updateMarker = function (marker) {
    //     return $http.put(urlBase + '/' + marker.ID, marker)
    // };

    // markers.deleteMarker = function (id) {
    //     return $http.delete(urlBase + '/' + id);
    // };

    return markers;
}]);
