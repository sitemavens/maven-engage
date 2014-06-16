<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'SettingsCached', $settingsCached ); ?>

<form role="form"  name="formSettings" ng-submit="saveSettings(settings)">

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">General</div>
					<div class="panel-body">

						<div class="alert alert-danger" ng-show="formSettings.$invalid">
							<span ng-show="formSettings.$error.required">Required elements</span>
							<span ng-show="formSettings.$error.invalid">Invalid elements</span>
						</div>

						<div class="form-group"  >
							<label for="emailNotification">Send notification to</label>						
							<input required type="text" class="form-control" ng-model="setting.emailNotificationsTo" name="emailNotification" />
						</div>
						<div class="form-group"  >
							<label for="enabled">Enabled</label>						
							<input type="checkbox" class="form-control" ng-model="setting.enabled" name="enabled" />
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="row">
			<div class="col-md-4">
				<button type="submit" ng-disabled="formSettings.$invalid" ng-click="saveSettings" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</div>


</form>