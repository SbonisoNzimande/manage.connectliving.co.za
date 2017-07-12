var app = angular.module("connectApp", [
			'ngRoute',
			'ngCookies',
			'ui.router',
			'pdfjsViewer',
			'ajoslin.promise-tracker'
		])

app.filter('trusted', ['$sce', function ($sce) {
    return function(url) {
        return $sce.trustAsResourceUrl(url);
    };
}])