<?php

$nav['contact'] = array('url' => '/contact', 'slug' => 'contact', 'name' => 'Contact', 'loggedInOnly' => false, 'weight' => 5); // -1 for only not logged in
if($slug == "contact") {
	Content::setContent(<<<EOT
	<h1>Contact Us!</h1>
	<h2>IRC</h2>
	Join our IRC chatroom!<br />
	Just point your favorite client to irc.esper.net, port 6667 and join #hRepo!
	<h2>Twitter</h2>
	Tweet us on Twitter! We are <a href="http://twitter.com/hRepo">hRepo</a>

EOT
	);
	
}
