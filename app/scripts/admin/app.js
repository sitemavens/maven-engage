'use strict';

angular
	.module('mavenEngageApp', [
		'ngCookies',
		'ngResource',
		'ngSanitize',
		'ngRoute',
		'ui.bootstrap',
		'mavenEngageApp.services'
	])
	.config(function($routeProvider) {
		console.log(Maven);
		$routeProvider
			.when('/', {
				templateUrl: Maven.viewHandlerUrl + 'mavenengage/dashboard/',
				controller: 'DashboardCtrl'
			})
			.when('/settings', {
				templateUrl: Maven.viewHandlerUrl + 'mavenengage/settings/',
				controller: 'SettingsCtrl'
			})
			.otherwise({
				redirectTo: '/'
			});
	});
