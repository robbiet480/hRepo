<?php

$nav['faq'] = array('url' => '/faq', 'slug' => 'faq', 'name' => 'FAQ', 'loggedInOnly' => false, 'weight' => 3);
if($slug == "faq") {
	Content::setContent("faq");
	
}