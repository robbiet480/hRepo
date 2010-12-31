<?php

$nav['upload'] = array('url' => '/upload', 'slug' => 'upload', 'name' => 'Upload', 'loggedInOnly' => 999, 'minRole' => 0, 'weight' => 4, 'extrapre' => '', 'extrapost' => ''); // 1 for only logged in
if ($slug == "upload")
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
		$pluginID = $dbQuery->fetchColumn(0);
		// Have they uploaded stuff before?
		$dbQuery2 = Database::select('plugin_downloads', '*', array('pid = ?', $pluginID));
		$message = '';
		if ($dbQuery2->rowCount() == 0) {
			$message = Message::notice('Hi there! It looks like this is the first time you\'ve uploaded files for this plugin. Simply select the files you wish to upload using the file selector below, and then provide details of your uploads in the form which will appear.');
		}
		Content::addAdditionalCSS('uploadify.css');
		Content::addAdditionalJS('jquery.uploadify.min.js');
		Content::setContent(<<<EOT
				<h1>Upload Files</h1>
				$message
				<div id="uploadBox"></div>
				<form action="/uploadComplete/$params[0]/$params[1]" method="POST">
				<div id="uploadFormArea"></div>
				</form>
				<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#uploadBox').uploadify(
						{
							'swf': '/static/images/uploadify/uploadify.swf',
							'uploader': '/upload/$params[0]/$params[1]/handleUpload/',
							'auto': true,
							'onUploadStart': function(file) {
								jQuery('#uploadFormArea').append(
									"<div id='"+file.id+"_details'><fieldset><legend>"+file.name+"</legend><label for='"+file.id+"_newname'>Filename:</label><input type='text' name='"+file.id+"_newname' id='"+file.id+"_newname' value='"+file.name+"' /><br /><label for='"+file.id+"_version'>File version:</label><input type='text' name='"+file.id+"_version' id='"+file.id+"_version' /><br /><label for='"+file.id+"_changelog'>File Description/Changelog:</label><textarea name='"+file.id+"_changelog' id='"+file.id+"_changelog' style='width: 100%; height: 250px;'></textarea></fieldset></div>"
								);
							},
							'onUploadSuccess': function(file, data, response) {
							},
							'onUploadError': function(file, errCode, errMsg) {
							}
						}
					);
					});
				</script>
EOT
				);
	}
}