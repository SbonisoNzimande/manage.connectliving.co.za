<div class="center-block w-xxl w-auto-xs p-v-md">
	<div class="navbar">
		<div class="navbar-brand m-t-lg text-center">
			<img src="../public/images/connect_living_logo.png" style="width: 50px; height: 50px;" />
		</div>
	</div>

	<div class="p-lg panel md-whiteframe-z1 text-color m">
		<div class="m-b text-sm">
			Password Recovery
		</div>
		<form name="form" id="ForgotPasswordForm">
			<div id="add_err"></div>
			<div class="md-form-group float-label">
				<input type="email" class="md-input" ng-model="user.email" id="email" required>
				<label>Email</label>
			</div>
			
			<div class="m-b-md "> 
				<button md-ink-ripple type="submit" class="md-btn md-raised yellow btn-block p-h-md">Send Reminder</button>
			</div>
			
		</form>
	</div>


</div>