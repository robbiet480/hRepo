<?php

if ($slug == "handleUpload")
{
	$pluginUsername = $params[0];
	$u = new XenForo_Model_User();
	$pluginUserID = $u->getUserIdFromUser($u->getUserByName($pluginUsername));
	$pluginName = $params[1];
	$dbQuery = Database::select('plugins', 'pid', array('pname = ? AND pauthor_id = ?', $pluginName, $pluginUserID));
	$pluginID = $dbQuery->fetchColumn();
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
		$fileMd5 = md5_file($tempFile);
		$newFileName = hash('whirlpool', $pluginUsername . '/' . $pluginName . '/' . $_FILES['Filedata']['name'] . file_get_contents($tempFile));
		$fileDir = '/home2/bukkit/fill/uploads/';
		if (file_exists($fileDir . $newFileName))
		{
			echo 'File exists';
			exit();
		}
		move_uploaded_file($tempFile, $fileDir . $newFileName);
		$a = Database::select('plugins_downloads', '*', array('dfname = ?', $_FILES['Filedata']['name']));
		$lastNum = 0;
		if ($a->rowCount() == 0)
		{
			Database::insert('plugins_downloads', array('pid' => $pluginID, 'dfname' => $_FILES['Filedata']['name'], 'dfriendlyname' => 'notdoneyet', 'ddesc' => 'notdoneyet'));
			$a = Database::select('plugins_downloads', '*', array('dfname = ?', $_FILES['Filedata']['name']));
			$pluginFileRow = $a->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			$pluginFileRow = $a->fetch(PDO::FETCH_ASSOC);
			$b = Database::select('plugins_downloads_version', 'vnumber', array('did = ?', $pluginFileRow['did']));
			$lastNum = $b['vnumber'];
		}
		Database::insert('plugin_downloads_version', array('did' => $pluginFileRow['did'], 'vnumber' => $lastNum + 1, 'vhash' => $fileMd5, 'vdate' => date('Y-m-d H:i:s'), 'vchangelog' => 'notdoneyet', 'isons3' => '0'));
		echo '1';
		exit();
	}
}