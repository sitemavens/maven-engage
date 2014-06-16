<div class="row" ng-repeat="stat in stats">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Emails Sent</div>
			<div class="panel-body">
				{{stat.sent}}
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Recovered Carts</div>
			<div class="panel-body">
				{{stat.recovered|number}}
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="{{stat.recoveredPercent|number}}" aria-valuemin="0" 
					     aria-valuemax="100" style="width: {{stat.recoveredPercent|number}}%;">
						{{stat.recoveredPercent|number}}%
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Completed Orders</div>
			<div class="panel-body">
				{{stat.completed|number}}
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="{{stat.completedPercent|number}}" aria-valuemin="0" 
					     aria-valuemax="100" style="width: {{stat.completedPercent|number}}%;">
						{{stat.completedPercent|number}}%
					</div>
				</div>
			</div>
		</div>
	</div>
</div>