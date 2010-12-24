<?php

if ($_SESSION['debugmode']) {
	Content::append('<h4>Debug details</h4><pre>'.print_r(Log::getLog(), true).'</pre>');
}