<?php

$nav['login'] = array('url' => '/login', 'slug' => 'login', 'name' => 'Login', 'loggedInOnly' => -1, 'weight' => 4); // -1 for only not logged in
if($slug == "login") {
	Content::setContent(<<<EOT
	<form action="#" method="POST">
	<label for="username">Username:</label><input type="text" name="username" id="username"><br />
	<label for="password">Password:</label><input type="password" name="password" id="password"><br />
	<input type="submit" value="Login">
	</form>


EOT
	);
	
}