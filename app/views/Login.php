<div class="center-block w-xxl w-auto-xs p-v-md">
	<div class="navbar">
		<div class="navbar-brand m-t-lg text-center">
			<img src="../public/images/connect_living_logo.png" style="width: 50px; height: 50px;" />
		</div>
	</div>

	<div class="p-lg panel md-whiteframe-z1 text-color m">
		<div class="m-b text-sm">
			Login
		</div>
		<form name="form" id="LoginForm">
			<div id="add_err"></div>
			<div class="md-form-group float-label">
				<input type="email" class="md-input" ng-model="user.email" id="email" required>
				<label>Email</label>
			</div>
			<div class="md-form-group float-label">
				<input type="password" class="md-input" ng-model="user.password" id="password" required>
				<label>Password</label>
			</div>      
			<div class="m-b-md">        
				<label class="md-check">
					<input type="checkbox"><i class="indigo"></i> Keep me signed in
				</label>
			</div>
			<button md-ink-ripple type="submit" class="md-btn md-raised yellow btn-block p-h-md">Sign in</button>
		</form>
	</div>


</div>