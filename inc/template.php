<?php

function url ($slug, $args = array()) {
	return sprintf("%s?page=%s", HR_PUB_ROOT, $slug);
}

function template () {
	header($_SERVER["SERVER_PROTOCOL"]." ".Content::$status);

	foreach(Content::$headers as $k => $v) {
		header($k.": ".$v);
	}
	
	if(Content::$useTemplate) {
		require HR_TEMPLATE.HR_TEMPLATE_TO_USE.'/index.php';
	}
	else {
		echo Content::$content;
	}
	
	exit();
}

function content () {
	return Content::$content;
}

function nav () {
	global $nav, $slug;
	usort($nav, function ($x,$y) {
		if($x['weight'] > $y['weight']) return 1;
		if($x['weight'] < $y['weight']) return -1;
		return 0;
	});
	
	$r = "<ul id='nav'>";
	foreach($nav as $thisSlug => $vals) {
		if(!($vals['visible'] === false) && (!$vals['loggedInOnly'] || $vals['loggedInOnly'] && User::$isValid || ($vals['loggedInOnly'] == -1) && !User::$isValid)) { 
			$r .= sprintf('<li%s><a href="%s">%s</a></li>', ($slug == $vals['slug'] ? ' class="active"' : ''),
															url($vals['url']),
															$vals['name']);
		}
	}
	return $r.'</ul>';
}

function pagetitle () {
	global $nav, $slug;
	return $nav[$slug]['name'];
}