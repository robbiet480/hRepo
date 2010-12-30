<?php

$nav['upload'] = array('url' => '/upload', 'slug' => 'upload', 'name' => 'Upload', 'loggedInOnly' => 999, 'minRole' => 0, 'weight' => 4, 'extrapre' => '', 'extrapost' => ''); // 1 for only logged in
if ($slug == "upload")
{
	$pluginUsername = $params[0];
	$u = new XenForo_Model_User();
	$pluginUserID = $u->getUserIdFromUser($u->getUserByName($pluginUsername));
	$pluginName = $params[1];
	$dbHandle = Database::getHandle();
	$dbQuery = $dbHandle->prepare('SELECT pl.pname, pl.pauthor_id FROM plugins AS pl WHERE pl.pname = ? AND pl.pauthor_id = ?');
	$dbRow = $dbQuery->execute(array($pluginName, $pluginUserID));
	if ((User::$role == -1 || User::$uid != $pluginUserID) && (User::$role != User::ROLE_ADMIN))
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