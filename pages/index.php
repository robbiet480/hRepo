<?php

$nav['index'] = array('url' => '/index', 'slug' => 'index', 'name' => 'Home', 'loggedInOnly' => false, 'weight' => -100, 'visible' => false);
if($slug == "index") {
	Content::setContent(<<<EOT
	<h1>Hello stranger!!!</h1>
	<p>You have reached the home of hRepo, the global mod repository for <a href="http://hey0.net">hMod</a>, the fabulous mod for <a href="http://minecraft.net">Minecraft</a>, the highly addictive online and single player 8-bit mining game</p>
	


EOT
	);
	
}