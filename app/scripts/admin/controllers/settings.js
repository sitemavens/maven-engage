'use strict';

angular.module('mavenEngageApp')
	.controller('SettingsCtrl', ['$scope', 'Setting', function($scope, Setting) {

			$scope.setting = Setting.get(1);
			//console.log($scope.setting);
			$scope.saveSettings = function() {
				$scope.setting.id = 1;
				$scope.setting.$save();
			};
		}]);