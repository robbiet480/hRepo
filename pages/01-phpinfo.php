<?php

if($slug == "phpinfol33tHAXX") {

	ob_start();
	phpinfo();
	$phpinfo = ob_get_contents();
	ob_end_clean();
	Content::setContent(<<<EOT
	<h1>LOL PHPINFO</h1>
	<p>$phpinfo</p>
	


EOT
	);
	
}
