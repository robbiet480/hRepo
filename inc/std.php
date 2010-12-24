<?php

function inc ($file) {
	Log::add("including file: ".$file);
	require(HR_INC.$file);
}

function inclib($file) {
	Log::add("including library file: ".$file);
	require(HR_LIB.$file);
}

function check_post () {
	$args = func_get_args();
	
	foreach($args as $a) {
		if(!array_key_exists($a, $_POST)) {
			return false;
		}
	}
	return true;
}

function redirect ($path, $relative = false) {
	if($relative) {
		header("Location: ".HR_PUB_ROOT.$path);
		exit();
	}
	header("Location: ".$path);
	exit();
}