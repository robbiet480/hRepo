<?php

$nav['faq'] = array('url' => '/faq', 'slug' => 'faq', 'name' => 'FAQ', 'loggedInOnly' => false, 'weight' => 3);
if($slug == "faq") {
	Content::setContent(<<<EOT
	<h1>1. Why is Sturmeh so cool?</h1>
	<p>Many scientists have tried to derive the answer to the question but even the world's best minds are unsure of what causes the effect.</p>
	<h1>2. Why is sk89q so lame?</h1>
	<p>Many scientists have tried to derive the answer to the question but even the world's best minds are unsure of what causes the effect. Also, his plugins suck</p>
	


EOT
	);
	
}