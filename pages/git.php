<?php

if($slug == "git") {
	chdir(HR_ROOT);
	Content::setContent("
	<h1>Updating website from git...</h1>
	<pre>".`echo Running git pull && git pull 2>&1`."</pre>


	");
	
}
