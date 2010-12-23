<?php

$logindropdown = <<<EOT
<div id="loginDropDown">
	<form action="/login" method="post">
		<label for="usernameBox">Username:</label>
		<input type="text" name="username" id="usernameBox" /><br />

		<label for="passwordBox">Password:</label>
		<input type="password" name="password" id="passwordBox" /><br />

		<span id="loginBoxRMe">
			<label for="rememberMeBox">Remember Me</label>
			<input type="checkbox" name="rememberMe" id="rememberMeBox" />
		</span>

		<span id="loginBoxLogin">
			<input type="submit" value="Login" />
		</span>
	</form>
</div>
EOT;

$nav['login'] = array('url' => '/login', 'slug' => 'login', 'name' => 'Login', 'loggedInOnly' => -1, 'weight' => 4, 'extrapre' => $logindropdown, 'extrapost' => ''); // -1 for only not logged in
if($slug == "login") {
	$message = User::loginHandle();
	Content::setContent(<<<EOT
	<h1>Login</h1>
	<form action="/login" method="post">$message
		<div class="form-row">
			<label for="username">Username</label>
			<span><input type="text" name="username" id="username" /></span>
		</div>
		<div class="form-row">
			<label for="password">Password</label>
			<span><input type="password" name="password" id="password" /></span>
		</div>
		<div class="form-row form-row-last">
			<span><input type="submit" name="login" value="Login" /></span>
		</div>
	</form>


EOT
	);
	
}
