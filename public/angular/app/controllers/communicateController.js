app.controller('CommunicateCtrl',
	['$state', '$filter', '$scope', '$rootScope', '$http', '$window', '$log', '$location', '$stateParams', '$timeout', 'promiseTracker',
	function ($state, $filter, $scope, $rootScope, $http, $window, $log, $location, $stateParams, $timeout, promiseTracker) {

		$scope.CreditCost 	= 0.36;
		$scope.CreditNumber = 0;
		// $scope.AmountDue 	= ($scope.CreditNumber * $scope.CreditCost);

		// console.log($scope.AmountDue);


		$scope.$watch('CreditCost * CreditNumber', function (price) {
		    $scope.AmountDue = $filter('currency')(price, 'R', 2);
		});


		$scope.submit_purchase = function (form){
			$scope.submitted = true;
			// Inititate the promise tracker to track form submissions.
			$scope.progress  = promiseTracker();

			console.log('Submiting purchase: ' + $scope.prop_id);

			var config = {
				  params : {
					'callback' : 'JSON_CALLBACK',
					'CreditNumber' : $scope.CreditNumber,
					'AmountDue' : $scope.AmountDue,
					'prop_id' : $scope.prop_id,
					'prop_name' : $scope.prop_name
				  },
			};

			// BuySMSCredits

			// Perform JSONP request.
			var $promise = $http.jsonp('http://manage.connectliving.co.za/Communicate/BuySMSCredits', config)
			  .success(function(data, status, headers, config) {

				if (data.status == true) {
					$scope.messages           = data.text;
					$scope.status             = true;
					$scope.submitted          = false;

					$scope.CreditNumber 	  = '';
					$scope.AmountDue 		  = '';
					
				} else {
					$scope.status             = false;
					$scope.messages           = 'Error processing the form: ' + data.text;
					$log.error(data);
				}
			  })

			  .error(function(data, status, headers, config) {
				$scope.progress 			  = data;
				$scope.messages 			  = 'There was a network error. Try again later.';
				$log.error(data);
			  })
			  
			  .finally(function() {
				// Hide status messages after three seconds.
				  if ($scope.status == true) {

				  	$scope.countDown = 3;    
				  	
				  	var output = '<div class="alert alert-success"><p>You are being redirected to PayFast to progress payment</p></div>';
				  	$("#buy_credits_err").html(output);
					$timeout(function() {
					
						window.location.href = $scope.messages;
					}, 3000);
				  }else{
				  	// $("#add_new_modal").scrollTop(0);

				  	var output = '<div class="alert alert-danger"><p>'+$scope.messages+'</p></div>';

				  	console.log($scope.messages);
				  	$("#buy_credits_err").html(output);
					

				  };

				  $timeout(function() {
				  	$("#buy_credits_err").html('');
				  	$scope.messages = null;
				  }, 3000);

				
			  });

			// Track the request and show its progress to the user.
			$scope.progress.addPromise($promise);

		};

		
	
}])