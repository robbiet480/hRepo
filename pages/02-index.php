<?php

$nav['index'] = array('url' => '/index', 'slug' => 'index', 'name' => 'Home', 'loggedInOnly' => false, 'weight' => -100, 'visible' => false);
if($slug == "index") {
	$uname = 'stranger!!';
	$message = '';
	if (isset($_SESSION['message'])) {
		$message = '<p>' . $_SESSION['message'] .'</p>';
		unset($_SESSION['message']);
	}
	if (User::isValid()) {
		$uname = User::$uname;
	}
	Content::setContent(<<<EOT
	<h1>Hello {$uname}!</h1>
	$message
	<p>You have reached the home of Fill The Bukkit, the global mod repository for <a href="http://bukkit.org">Bukkit</a>, the fabulous mod for <a href="http://minecraft.net">Minecraft</a>, the highly addictive online and single player 8-bit mining game</p>
	


EOT
	);
	
}
