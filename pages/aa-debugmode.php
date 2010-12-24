<?php

if (!isset($_SESSION['debugmode']))
	$_SESSION['debugmode'] = false;

if ($slug == "debugmode") {
	if (!isset($params[0])) {
		echo "Your current debug mode state is: " . ($_SESSION['debugmode'] ? 'ON' : 'OFF');
		echo "<form action='/debugmode/".(!$_SESSION['debugmode'] ? 'on' : 'off')."'><input type='submit' value='Click to toggle' /></form>";
	} else if ($params[0] == 'on') {
		$_SESSION['debugmode'] = true;
		redirect('/debugmode', true);
	} else {
		$_SESSION['debugmode'] = false;
		redirect('/debugmode', true);
	}
}
