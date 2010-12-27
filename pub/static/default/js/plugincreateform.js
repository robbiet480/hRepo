$(document).ready(function(){
	$('#ismyplugin').change(function() {
		if ($('#ismyplugin:checked').length == 0) { // not checked, isn't his
			$('#pauthornameRow').slideDown(); // hide
		} else { // checked, is his
			$('#pauthornameRow').slideUp(); // show
		}
	});
});