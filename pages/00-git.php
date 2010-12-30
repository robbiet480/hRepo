<?php

if($slug == "git") {
	Content::$forcedTitle = "Git Update";
	if (count($params) > 0) {
		chdir(HR_ROOT);
		Content::setContent("
			<h1>Updating website from git...</h1>
			<pre>".`echo Running svn revert && svn revert -R . 2>&1 && echo Running svn update && svn update`."</pre>
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
