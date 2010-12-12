<?php

$nav['login'] = array('url' => '/login', 'slug' => 'login', 'name' => 'Login', 'loggedInOnly' => -1, 'weight' => 4); // -1 for only not logged in
if($slug == "login") {
	Content::setContent(<<<EOT
	<form action="#" method="POST">
	<label for="username" style="font-size:12pt;font-weight:bold;">Username:</label><input style="font-size:18pt;" size="25" type="text" name="username" id="username"><br /><br />
	<label for="password" style="font-size:12pt;font-weight:bold;">Password:</label><input style="font-size:18pt;" size="25" type="password" name="password" id="password"><br />
	<input type="submit" value="Login">
	</form>


EOT
	);
	
}
