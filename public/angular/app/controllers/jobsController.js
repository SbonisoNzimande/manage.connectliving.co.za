app.controller('JobsCtrl',
	['$state', '$filter', '$scope', '$rootScope', '$http', '$window', '$log', '$location', '$stateParams', '$timeout', 'promiseTracker', '$sce',
	function ($state, $filter, $scope, $rootScope, $http, $window, $log, $location, $stateParams, $timeout, promiseTracker, $sce) {

		$scope.url 				= 'Jobs/GetJobQuotes';
		$scope.url_vote			= 'Jobs/Vote';
		$scope.email_url 		= 'Jobs/EmailJobQuote';
		$scope.google_url 		= 'https://docs.google.com/viewer?embedded=true&pid=explorer&efh=false&a=v&chrome=false&embedded=true&url='
		var page_name 			= location.pathname.split('/').slice(-1)[0];

		console.log('PAGE NAME NG', page_name);
		$scope.get_job_quotes 	= function (job_id, prop_id){

			console.log('TEST:', job_id, prop_id);

			$http.get($scope.url, {params:{job_id: job_id, prop_id: prop_id}}).
				then(function(response) {
					console.log('JOB QUets', response);

					$scope.quote_list = response.data;
				}, function(error) {
					console.log('ERROR', response);
			});

		}

		$scope.email_trustee 	= function (company_id, prop_id, property_name, property_address, job_id, job_description, quote_id, file_name) {

			console.log (company_id, prop_id, property_name, property_address, job_id, job_description, quote_id, file_name);

			var params = {
				company_id: company_id, 
				prop_id: prop_id, 
				job_id: job_id, 
				job_description: job_description, 
				quote_id: quote_id,
				property_name: property_name,
				property_address: property_address,
				file_name: file_name
			}

			$http.get($scope.email_url, {params:params}).
				then(function(response) {
					alert('Email Sent To Trustees');

					// $scope.quote_list = response.data;
				}, function(error) {
					alert('Error sending email');
					console.log('ERROR EMAIL', response);
			});

		}

		$scope.vote_up = function (qoute_id) {
			

			console.log('VOTE UP', qoute_id);

			var params = {
				type: 'up', 
				qoute_id: qoute_id,
				user_id: $scope.UserID,
			}

			$http.get($scope.url_vote, {params:params}).
				then(function(response) {
					alert('Voted Up');

					// $scope.quote_list = response.data;
					$scope.get_job_quotes($scope.job_id, $scope.prop_id);
				}, function(error) {
					alert('Voting up');
					console.log('ERROR EMAIL', response);
			});

		}

		$scope.vote_down = function (qoute_id) {
			

			console.log('VOTE UP', qoute_id);

			var params = {
				type: 'down', 
				qoute_id: qoute_id,
				user_id: $scope.UserID,
			}

			$http.get($scope.url_vote, {params:params}).
				then(function(response) {
					alert('Voted Down');

					// $scope.quote_list = response.data;
					$scope.get_job_quotes ($scope.job_id, $scope.prop_id);
				}, function(error) {
					alert('Voting Down Error');
					console.log('ERROR EMAIL', response);
			});

		}

		console.log('PAGE NAME NG', page_name);

		if (page_name === 'JobQuotesTrusteeVoting') {
			$scope.job_id 		= $('#job_id').val();
			$scope.prop_id		= $('#prop_id').val();

			$scope.get_job_quotes ($scope.job_id, $scope.prop_id);
		}

		



		$('#JobQuotesModal').on('show.bs.modal', function(e) {// on modal open
			$scope.job_id 		= $(e.relatedTarget).data('job-id');
			$scope.prop_id		= $(e.relatedTarget).data('prop-id');

			$scope.get_job_quotes ($scope.job_id, $scope.prop_id);
		});
}])