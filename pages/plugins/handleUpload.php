<?php

if ($slug == "handleUpload")
{
	$pluginUsername = $params[0];
	$u = new XenForo_Model_User();
	$pluginUserID = $u->getUserIdFromUser($u->getUserByName($pluginUsername));
	$pluginName = $params[1];
	$dbQuery = Database::select('plugins', 'pid', array('pname = ? AND pauthor_id = ?', $pluginName, $pluginUserID));
	if ((User::$role == User::ROLE_GUEST || User::$uid != $pluginUserID) && (User::$role != User::ROLE_ADMIN))
	{
		$httpError = 403;
	}
	else if ($dbQuery->rowCount() != 1)
	{
		$httpError = 404;
	}
	else
	{
		// okay, let's do this
		// get down on it
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$newFileName = hash('whirlpool', $pluginUsername . '/' . $pluginName . '/' . $_FILES['Filedata']['name']);
		$fileDir = '/home2/bukkit/fill/uploads/';
		if (file_exists($fileDir . $newFileName)) {
			echo 'File exists';
			exit();
		}
		move_uploaded_file($tempFile, $fileDir . $newFileName);
		
		echo '1';
		exit();
	}
}