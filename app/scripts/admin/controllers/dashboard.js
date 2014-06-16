'use strict';

angular.module('mavenEngageApp')
	.controller('DashboardCtrl', ['$scope', 'Stat', function($scope, Stat) {
			$scope.stats = Stat.get();
		}]);
