<?php

$nav['upload'] = array('url' => '/upload', 'slug' => 'upload', 'name' => 'Upload', 'loggedInOnly' => 999, 'minRole' => 0, 'weight' => 4, 'extrapre' => '', 'extrapost' => ''); // 1 for only logged in
if ($slug == "upload")
{
	$pluginUsername = $params[0];
	$pluginName = $params[1];
	$dbHandle = Database::getHandle();
	$dbQuery = $dbHandle->prepare('SELECT pl.pname, us.username FROM plugins AS pl LEFT JOIN users ON pl.pauthor_id = us.uid WHERE pl.pname = ? AND us.username = ?');
	$dbRow = $dbQuery->execute(array($pluginName, $pluginUsername));
	if (User::$role == -1 || User::$uname != $pluginUsername)
	{
		$httpError = 403;
	}
	else if ($dbRow->rowCount() != 1)
	{
		$httpError = 404;
	}
	else
	{
		
	}
}