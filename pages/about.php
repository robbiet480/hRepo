<?php

$nav['about'] = array('url' => '/about', 'slug' => 'about', 'name' => 'About', 'loggedInOnly' => false, 'weight' => 2);
if($slug == "about") {
	Content::setContent(<<<EOT
						<h2>About</h2>
						<h3>About the team</h3> 
 
<h4>Robbie Trencheny</h4> 
<p>Owner of MinecraftServers.com & Hostiio. Web devloper by day, sysadmin by night</p> 
 
<img alt="Robbie Trencheny" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>Project Founder</dd> 
	
	<dt>IRC Nick</dt> 
	<dd>robbiet480</dd> 
	
	<dt>Coding languages</dt> 
	<dd>PHP, MySQL, HTML, CSS</dd> 
</dl> 
 
<h4>Member 2</h4> 
<p>Bla bla bla</p> 
 
<img alt="Member 2" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>Something or other</dd> 
	
	<dt>IRC Nick</dt> 
	<dd>blablabla</dd> 
	
	<dt>Coding languages</dt> 
	<dd>A B C</dd> 
</dl>
EOT
	);
	
}