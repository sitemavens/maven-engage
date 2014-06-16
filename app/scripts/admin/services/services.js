'use strict';
var admin = angular.module('mavenEngageApp.services', ['ngResource']);

admin.factory('Setting', ['$resource', function($resource) {
		return $resource('/wp-json/mavenengage/settings/:id', {id: '@id'}, {
			get: {
				method: "GET",
				cache: true
			}
		});
	}]);

admin.factory('Stat', ['$resource', function($resource) {
		return $resource('/wp-json/mavenengage/dashboard', {}, {
			get: {
				method: "GET",
				cache: true,
				isArray: true
			}
		});
	}]);