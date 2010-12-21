<?php

$nav['about'] = array('url' => '/about', 'slug' => 'about', 'name' => 'About', 'loggedInOnly' => false, 'weight' => 2);
if($slug == "about") {
	Content::setContent(<<<EOT
						<h1>About</h1>
						<h3>About the team</h3> 
 
<h4>Robbie Trencheny</h4> 
<p>Owner of MinecraftServers.com & Hostiio. Web devloper by day, sysadmin by night</p> 
 
<img alt="Robbie Trencheny" class="bioicon" src="http://hrepo.com/static/images/bio/robbie.png" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>Project Founder</dd> 
	
	<dt>IRC Nick</dt> 
	<dd>robbiet480</dd> 
	
	<dt>Coding languages</dt> 
	<dd>PHP, MySQL, HTML, CSS</dd> 
</dl> 
 
<h4>emirin</h4> 
<p>Bla bla bla</p> 
 
<img alt="Member 2" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>sql dev</dd> 
	
	<dt>IRC Nick</dt> 
	<dd>emirin_</dd> 
	
	<dt>Coding languages</dt> 
	<dd>PHP, SQL</dd> 
</dl>

<h4>Alec Gorge</h4> 
<p>High school student by day, programmer by night</p> 
 
<img alt="Member 2" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>Programmer</dd> 
	
	<dt>IRC Nick</dt> 
	<dd>alecgorge</dd> 
	
	<dt>Coding languages</dt> 
	<dd>PHP, MySQL, HTML, CSS, Python, Objective-C, JavaScript/ECMAScript, Java, C#</dd> 
</dl>

<h4>Sturmeh</h4> 
<p>Am I doin it right?</p> 
 
<img alt="Member 2" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>Programmer</dd> 
	
	<dt>IRC Nick</dt> 
	<dd>Sturmeh</dd> 
	
	<dt>Coding languages</dt> 
	<dd>Java, PHP, MySQL</dd> 
</dl>

<h4>Organik</h4> 
<p>I'll design your life</p> 
 
<img alt="Member 2" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>Designer/Graphics</dd> 
	
	<dt>IRC Nick</dt> 
	<dd>Organik</dd> 
	
	<dt>Coding languages</dt> 
	<dd>Photoshop, CSS, HTML, SQL</dd> 
</dl>

<h4>lukegb</h4>
<p>Lean, mean, teen machine</p>

<img alt="Oh hai" class="bioicon" src="http://placehold.it/57x57" />
<dl class="bioinfo">
        <dt>Title</dt>
        <dd>Programmer</dd>

        <dt>IRC Nick</dt>
        <dd>lukegb</dd>

        <dt>Coding languages</dt>
        <dd>(X)HTML, CSS, SQL, PHP, C#, Python, JavaScript/ECMAScript, Java</dd>
</dl>

<h4>GoMySQL</h4> 
<p>NULL</p> 

<img alt="GoMySQL" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>MySQL DBA</dd> 

	<dt>IRC Nick</dt> 
	<dd>GoMySQL</dd> 

	<dt>Coding languages</dt> 
	<dd>SQL, PHP, Perl, Bash</dd>
</dl>


<h4>Shadow386</h4> 
<p>Cookies, pie and everything sweet</p> 

<img alt="GoMySQL" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>Graphic Artist</dd> 

	<dt>IRC Nick</dt> 
	<dd>Shadow386</dd> 

	<dt>Coding languages</dt> 
	<dd>"itty bitty bits of java, some c++ knowledge, php and mysql knowledge, and how to draw a decent picture"</dd>
</dl>

EOT
	);
	
}
