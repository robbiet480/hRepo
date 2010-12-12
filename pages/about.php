<?php

$nav['about'] = array('url' => '/about', 'slug' => 'about', 'name' => 'About', 'loggedInOnly' => false, 'weight' => 2);
if($slug == "about") {
	Content::setContent(<<<EOT
						<h2>About</h2>
						<h3>About the team</h3> 
 
<h4>Member 1</h4> 
<p>Bla bla bla</p> 
 
<img alt="Member 1" class="bioicon" src="http://placehold.it/57x57" /> 
<dl class="bioinfo"> 
	<dt>Title</dt> 
	<dd>Something or other</dd> 
	
	<dt>IRC Nick</dt> 
	<dd>blablabla</dd> 
	
	<dt>Coding languages</dt> 
	<dd>X Y Z</dd> 
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