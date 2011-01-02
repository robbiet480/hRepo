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
		$newFileName = $fileMd5;
		$fileDir = '/tmp/';
		if (file_exists($fileDir . $newFileName))
		{
			echo 'File exists';
			exit();
		}
		file_put_contents('/tmp/step1.txt', 'Access SELECT: dfname = ' . $_FILES['Filedata']['name']);
		$a = Database::select('plugin_downloads', '*', array('dfname = ?', $_FILES['Filedata']['name']));
		file_put_contents('/tmp/step2.txt', 1);
		$lastNum = 0;
		if ($a->rowCount() == 0)
		{
			file_put_contents('/tmp/step3a.txt', 1);
			Database::insert('plugin_downloads', array('pid' => $pluginID, 'dfname' => $_FILES['Filedata']['name'], 'dfriendlyname' => 'notdoneyet', 'ddesc' => 'notdoneyet'));
			$a = Database::select('plugin_downloads', '*', array('dfname = ?', $_FILES['Filedata']['name']));
			$pluginFileRow = $a->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			file_put_contents('/tmp/step3b.txt', 1);
			$pluginFileRow = $a->fetch(PDO::FETCH_ASSOC);
			$b = Database::select('plugins_downloads_version', 'vnumber', array('did = ?', $pluginFileRow['did']));
			$lastNum = $b->fetchColumn();
		}
		file_put_contents('/tmp/step4.txt', 1);
		Database::insert('plugin_downloads_version', array('did' => $pluginFileRow['did'], 'vnumber' => $lastNum + 1, 'vhash' => $fileMd5, 'vdate' => date('Y-m-d H:i:s'), 'vchangelog' => 'notdoneyet', 'isons3' => '0'));
		file_put_contents('/tmp/step5.txt', 1);
		move_uploaded_file($tempFile, $fileDir . $newFileName);
		file_put_contents('/tmp/step6.txt', 1);
		echo '1';
		exit();
	}
}