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
		$session = array(
			'name' => session_name(),
			'id' => session_id()
		);
		Content::setContent(<<<EOT
				<h1>Upload Files</h1>
				$message
				<div id="uploadBox"></div>
				<form action="/uploadComplete/$params[0]/$params[1]" method="POST" id="uploadFormForm">
				<div id="uploadFormArea"></div>
				<button type="submit" id="bigSubmitButton" disabled="disabled">SUBMIT</button>
				</form>
				<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#uploadBox').uploadify(
						{
							'swf': '/static/images/uploadify/uploadify.swf',
							'uploader': '/upload/$params[0]/$params[1]/handleUpload/?{$session['name']}={$session['id']}',
							'auto': true,
							'multi': true,
							'cancelImage': '/static/images/uploadify/uploadify-cancel.png',
							'onSelect': function(file) {
								makeTheRow = "<div id='"+file.id+"_details'><fieldset><legend>"+file.name+"</legend>";
								makeTheRow = makeTheRow + "<div id='"+file.id+"_currentStatus'></div><br /><div id='"+file.id+"_newUploadForm'>";
								makeTheRow = makeTheRow + "<label for='"+file.id+"_newname'>Filename:</label><input type='text' name='"+file.id+"_newname' id='"+file.id+"_newname' value='"+file.name+"' /></div>";
								makeTheRow = makeTheRow + "<br /><label for='"+file.id+"_version'>File version:</label><input type='text' name='"+file.id+"_version' id='"+file.id+"_version' /><br />";
								makeTheRow = makeTheRow + "<label for='"+file.id+"_changelog'>File Description/Changelog:</label><textarea name='"+file.id+"_changelog' id='"+file.id+"_changelog' style='width: 100%; height: 250px;'></textarea></fieldset></div>";
								jQuery('#uploadFormArea').append(
									makeTheRow
								);
								jQuery("#"+file.id+"_currentStatus").slideDown();
							},
							'onUploadStart': function(file) {
								jQuery('#' + file.id + '_currentStatus').html("<div class='message message-info'><p>Upload beginning...</p></div>");
								uploadInProgress = true;
							},
							'onUploadSuccess': function(file, data, response) {
								jQuery('#' + file.id + '_currentStatus').html("<div class='message message-success'><p>Upload complete!</p></div>");
								itemsSubmitted = itemsSubmitted + 1;
							},
							'onUploadError': function(file, errCode, errMsg) {
								jQuery('#' + file.id + '_currentStatus').html("<div class='message message-error'><p>Upload failed...</p></div>");
								if (errCode == SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
									jQuery("#"+file.id+"_currentStatus").slideUp(function() {
										jQuery('#' + file.id + '_details').remove();
									});
								}
							},
							'onUploadComplete': function(file, queue) {
								uploadInProgress = (queue.queueLength > 0);
								if (!uploadInProgress) {
									$('#bigRedButton').removeAttr('disabled');
								} else {
									$('#bigRedButton').attr('disabled', 'disabled');
								}
							}
						});
						jQuery('#uploadFormForm').submit(function() {
							if (uploadInProgress == true) {
								alert('An upload is currently in progress. Please wait until it has completed before submitting the form.');
								return false;
							}
							if (itemsSubmitted == 0) {
								alert('You must upload something before you can submit this form.');
								return false;
							}
							return true;
						});
					});
					uploadInProgress = false;
					itemsSubmitted = 0;
				</script>
EOT
				);
	}
}