<?php

$nav['contact'] = array('url' => '/contact', 'slug' => 'contact', 'name' => 'Contact', 'loggedInOnly' => false, 'weight' => 5); // -1 for only not logged in
if($slug == "contact") {
	Content::setContent(<<<EOT
	<h1>IRC</h1>
	Join our IRC chatroom!<br />
	Just point your favorite client to irc.esper.net, port 6667 and join #hRepo!
	<h1>Twitter</h1>
	Tweet us on Twitter! We are <a href="http://twitter.com/hRepo">hRepo</a>

EOT
	);
	
}