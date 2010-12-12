<?php

if (empty(Content::$content)) {
	$httpError = 404;
}

if ($httpError == 404) {
	Content::$status = "404 Not Found";
	Content::setcontent(<<<EOT
	<h2>404 Error</h2>
	<p>You suck.</p>
EOT
	);
}
