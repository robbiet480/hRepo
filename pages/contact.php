<?php

$nav['contact'] = array('url' => '/contact', 'slug' => 'contact', 'name' => 'Contact', 'loggedInOnly' => false, 'weight' => 5); // -1 for only not logged in
if($slug == "contact") {
	Content::setContent("contact");
	
}