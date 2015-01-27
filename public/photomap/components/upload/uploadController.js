app.controller('UploadController', ['$scope', '$upload', function($scope, $upload) {

	$scope.myFiles = new Array();

	// @todo Lots of cleanup! 
	// @todo Actual error handling
	$scope.$watch('myFiles', function() {
	    for (var i = 0; i < $scope.myFiles.length; i++) {
	      var file = $scope.myFiles[i];
	      $scope.upload = $upload.upload({
	        url: 'api/v1.0/photos', // upload.php script, node.js route, or servlet url
	        method: 'POST',
	        //headers: {'Authorization': 'xxx'}, // only for html5
	        //withCredentials: true,
	        data: {photo: file, create_marker: true},
	        // file: file, // single file or a list of files. list is only for html5
	        //fileName: 'doc.jpg' or ['1.jpg', '2.jpg', ...] // to modify the name of the file(s)
	        //fileFormDataName: myFile, // file formData name ('Content-Disposition'), server side request form name
	                                    // could be a list of names for multiple files (html5). Default is 'file'
	        //formDataAppender: function(formData, key, val){}  // customize how data is added to the formData. 
	                                                            // See #40#issuecomment-28612000 for sample code

	      }).progress(function(evt) {
	        console.log('progress: ' + parseInt(100.0 * evt.loaded / evt.total) + '% file :');
	      }).success(function(data, status, headers, config) {
	        // file is uploaded successfully
console.dir(data);
	        console.log('file is uploaded successfully.');
	      });
	      //.error(...)
	      //.then(success, error, progress); // returns a promise that does NOT have progress/abort/xhr functions
	      //.xhr(function(xhr){xhr.upload.addEventListener(...)}) // access or attach event listeners to 
	                                                              //the underlying XMLHttpRequest
	    }


	  });
}]);
