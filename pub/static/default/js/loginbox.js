
$(function(){
	toModify = '#topBarLinkLogin';
	$(toModify).append(' <span id="loginArrowHead">&#x2303;</span>');
	$(toModify + ' a').click(function() {
		$('#loginDropDown').slideToggle();
		return false;
	});
});
