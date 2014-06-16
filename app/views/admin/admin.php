<div ng-app="mavenEngageApp" class="wrap">

	<div class="header" ng-controller='MainNavCtrl'>
		<ul class="nav nav-pills pull-right" >
			<li class="" ng-class="{active:isActive('/')}"><a ng-href="#/"><i class="glyphicon glyphicon-dashboard"></i>&nbsp;Dashboard</a></li>
			<li class="" ng-class="{active:isActive('/settings')}"><a ng-href="#/settings"><i class="glyphicon glyphicon-cog"></i>&nbsp;Settings</a></li>
		</ul>
		<h1 class="text-muted"><img src="http://www.sitemavens.com/wp-content/uploads/2014/05/SM-logo-transparent_rev.png" alt="SiteMavens"></h1>
	</div>

	<!-- Add your site or application content here -->
	<div data-loading >Loading...</div>
	<div ng-view></div>
</div>
