<?php

$nav['logout'] = array('url' => '/logout', 'slug' => 'logout', 'name' => 'Logout', 'loggedInOnly' => 1, 'weight' => 4, 'extrapre' => '', 'extrapost' => ''); // 1 for only logged in
if ($slug == 'logout')
{
	// Shred the evidence:
	// 1) session
	// 2) remember me cookie
	// 3) stuff in User
	User::logout();
	$_SESSION['message'] = Message::success('You have been logged out successfully.');
	redirect('/index', true);
}