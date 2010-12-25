<?php

$nav['logout'] = array('url' => '/logout', 'slug' => 'logout', 'name' => 'Logout', 'loggedInOnly' => 1, 'weight' => 4, 'extrapre' => '', 'extrapost' => ''); // 1 for only logged in
if ($slug == 'logout')
{
	// Shred the evidence:
	// 1) session
	// 2) remember me cookie
	$_SESSION = array(); // this clears all session variables
	if (ini_get("session.use_cookies"))
	{
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
		);
	}
	session_destroy(); // okay, that takes care of the session
	setcookie('ln', '', time() - 42000);
	setcookie('ln_iv', '', time() - 42000); // and that takes care of remember me
}
?>
