<?php

$nav['index'] = array('url' => '/index', 'slug' => 'index', 'name' => 'Home', 'loggedInOnly' => false, 'weight' => -100, 'visible' => false);
if($slug == "index") {
	Content::setContent("index.php");
}