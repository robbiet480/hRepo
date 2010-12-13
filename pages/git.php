<?php

if($slug == "git") {
	Content::$forcedTitle = "Git Update";
	if (count($params) > 0 && strtolower($params[0]) == hash('whirlpool', 'http://github.com/robbiet480/hRepo/')) {
		chdir(HR_ROOT);
		Content::setContent("
			<h1>Updating website from git...</h1>
			<pre>".`echo Running git pull && git pull 2>&1`."</pre>
		");
	} else {
		ob_start();
		print_r($parts);
		$dnote = ob_get_contents();
		ob_end_clean();
		Content::setContent("
			<h1>Authorisation code incorrect or missing</h2>
			<p>Git update did not go through.</p>
			<!-- Debug note: $dnote -->
		");
	}
}
