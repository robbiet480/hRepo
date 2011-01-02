jQuery(document).ready(function() {
	jQuery('#uploadBox').uploadify(
	{
		'swf': '/static/images/uploadify/uploadify.swf',
		'uploader': uploadURI,
		'auto': true,
		'multi': true,
		'cancelImage': '/static/images/uploadify/uploadify-cancel.png',
		'onSelect': function(file) {
			makeTheRow = "<div id='"+file.id+"_details'><fieldset><legend>"+file.name+"</legend>";
			makeTheRow = makeTheRow + "<div id='"+file.id+"_currentStatus'></div>";
			//makeTheRow = makeTheRow + "<br /><div id='"+file.id+"_newUploadForm'><label for='"+file.id+"_newname'>Friendly name:</label><input type='text' name='"+file.id+"_newname' id='"+file.id+"_newname' value='"+file.name+"' /></div>";
			makeTheRow = makeTheRow + "<input type='hidden' name='"+file.id+"_name' value='"+file.name+"' />";
			makeTheRow = makeTheRow + "<br /><label for='"+file.id+"_version'>File version:</label><input type='text' name='"+file.id+"_version' id='"+file.id+"_version' /><br />";
			makeTheRow = makeTheRow + "<label for='"+file.id+"_changelog'>File Description/Changelog:</label><textarea name='"+file.id+"_changelog' id='"+file.id+"_changelog' style='width: 100%; height: 250px;'></textarea></fieldset></div>";
			jQuery('#uploadFormArea').append(
			makeTheRow
		);
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
				jQuery('#' + file.id + '_details').remove();
			}
		},
		'onUploadComplete': function(file, queue) {
			uploadInProgress = (queue.queueLength > 0);
			if (!uploadInProgress) {
				$('#bigSubmitButton').removeAttr('disabled');
			} else {
				$('#bigSubmitButton').attr('disabled', 'disabled');
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