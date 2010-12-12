<?php

$nav['login'] = array('url' => '/login', 'slug' => 'login', 'name' => 'Login', 'loggedInOnly' => -1, 'weight' => 4); // -1 for only not logged in
if($slug == "login") {
	Content::setContent("login");
}