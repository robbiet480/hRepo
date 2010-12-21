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
}
