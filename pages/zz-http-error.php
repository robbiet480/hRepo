<?php

if (empty(Content::$content)) {
	$httpError = 404;
}

if ($httpError == 404) {
	Content::$forcedTitle = "404 Error";
	Content::$status = "404 Not Found";
	Content::setcontent(<<<EOT
	<h1>404 Error</h1>
	<p>You suck.</p>
EOT
	);
} else if ($httpError == 403) {
	Content::$forcedTitle = "403 Unauthorised";
	Content::$status = "403 Unauthorised";
	Content::setContent(<<<EOT
	<h1>Access Denied</h1>
	<p>You do not have the correct privileges to access this area.</p>
	<p>Maybe you need to log in?</p>
EOT
	);
}
