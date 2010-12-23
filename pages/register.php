<?php

//$nav['register'] = array('url' => '/register', 'slug' => 'register', 'name' => 'Register', 'loggedInOnly' => -1, 'weight' => 4); // -1 for only not logged in
// This should probably be part of the login dropdown?
if($slug == "register") {
	$message = User::registerHandle();
	Content::setContent(<<<EOT
	<h1>Register</h1>
	<form action="/register" method="post">$message
		<div class="form-row">
			<label for="username">Username</label>
			<span><input type="text" name="username" id="username" /></span>
		</div>
		<div class="form-row">
			<label for="password">Password</label>
			<span><input type="password" name="password" id="password" /></span>
		</div>
		<div class="form-row form-row-last">
			<span><input type="submit" name="register" value="Register" /></span>
		</div>
	</form>


EOT
	);
	
}
