<?php

//$nav['register'] = array('url' => '/register', 'slug' => 'register', 'name' => 'Register', 'loggedInOnly' => -1, 'weight' => 4); // -1 for only not logged in
// This should probably be part of the login dropdown?
if($slug == "register") {
	$message = User::registerHandle();
	$_SESSION['message'] = $message;
	redirect('login?regMessage=true', true);
}
