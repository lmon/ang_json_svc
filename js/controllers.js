//controllers.js
angular.module('App', ['$strap.directives'])
.controller('AppCtrl', function($scope, $http) {
	$scope.config = {
		svcUrl: "/victimizer/service/?appid=1&action="
	}
	
	$scope.victim = 'none';  
	$scope.ulclass='notready';

  	// onload data req
  	$http({method:'GET', url: $scope.config.svcUrl + 'getdata' }).
  		success(function(json) {
			$scope.phones = json.data;	
			//displayu list
			$scope.ulclass='ready';
	}).
	error(function(data, status, headers, config) {
    	console.log('Error')
    	// called asynchronously if an error occurs
    // or server returns response with an error status.
  	});

	$scope.updateUserClear = function($uid) {
		 console.log('clearing user');

		// onload data req
		$http({method:'GET', url: $scope.config.svcUrl +  'updatedata&id='+$uid+'&n=used&v=0'}).
			success(function(json) {
			//re-write the list
			$scope.phones = json.data;	
		}).
		error(function(data, status, headers, config) {
			console.log('Error 3')
			// called asynchronously if an error occurs
			// or server returns response with an error status.
		});
	}

	$scope.updateUser = function($uid) {
		 console.log('updating user');

		// onload data req
		$http({method:'GET', url: $scope.config.svcUrl + 'updatedata&id='+$uid+'&n=used&v=1'}).
			success(function(json) {
			//re-write the list
			$scope.phones = json.data;	
		}).
		error(function(data, status, headers, config) {
			console.log('Error 2')
			// called asynchronously if an error occurs
			// or server returns response with an error status.
		});
	}

	// rand button
	$scope.getAvails = function() {
		console.log('before: '+$scope.victim);
	
	  // select group of names that do not have used:1
	  	$scope.avail = [];
		for(var i=0;i<$scope.phones.length;i++) {
		  if($scope.phones[i].used!=1){
			$scope.avail.push($scope.phones[i].name);
			}  
	 	}
	 if($scope.avail.length > 0){

	    var n = Math.floor((Math.random()*$scope.avail.length));
 		$scope.victim = $scope.avail[n] ;

 		//update our list
 		$ob = $scope.findUserByName($scope.avail[n]);
 		
 		$scope.updateUser($ob.index); 
 
	 }else{
		 console.log('no one left');
	 }
	}
	
	$scope.findUserByName = function($name) {	
		for(var i=0;i<$scope.phones.length;i++) {
		  if($scope.phones[i].name == $name ){
			return {object: $scope.phones[i], index:i};
			}  
	 	}
	 	return null;
	}
	
});


