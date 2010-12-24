<?php

if($slug == "register") {
	inclib('recaptchalib.php');
	$message = '';
	$valerr = User::validateRegisterForm(); // this returns an array of validation errors
	// I don't really think CAPTCHA handling should be part of the user registration system inside the user object, so I'm going to handle it here.
	$resp = recaptcha_check_answer ($_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
	if ($resp->is_valid && count($valerr) == 0) { // hooray, code correct and no validation errors, carry on!
			$message .= User::registerHandle();
	} else if (!$resp->is_valid) {
		$valerr[] = 'The CAPTCHA code did not match what was displayed.';
		$_SESSION['recaperror'] = $resp->error;
	}
	if (count($valerr) != 0) { // let's generate the validation error message
		$message .= Message::validation($valerr);
	}
	$_SESSION['message'] = $message;
	redirect('/login/fromRegister', true);
}
