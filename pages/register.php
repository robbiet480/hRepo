<?php

if ($slug == "register")
{
	if ($params[0] == 'checkusername') {
		$pds = Database::select('users', array('uid'), array('username = ?', $_POST['username']));
		$valerr = array();
		$valerr['username'] = true;
		if ($pds->rowCount() != 0) {
			$valerr['username'] = 'Username is already taken. :(';
		}
		echo $valerr['username'];
		exit();
	}
	inclib('recaptchalib.php');
	$message = '';
	$valerr = User::validateRegisterForm(); // this returns an array of validation errors
	// I don't really think CAPTCHA handling should be part of the user registration system inside the user object, so I'm going to handle it here.
	$resp = recaptcha_check_answer($_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
	if ($resp->is_valid && count($valerr) == 0)
	{ // hooray, code correct and no validation errors, carry on!
		$message .= User::registerHandle($_POST['username'], $_POST['password'], $_POST['email']);
	}
	else if (!$resp->is_valid)
	{
		$valerr['captcha'] = 'The CAPTCHA code did not match what was displayed.';
		$_SESSION['recaperror'] = $resp->error;
	}
	unset($_SESSION['validatorPassback']);
	if (count($valerr) != 0)
	{ // let's generate the validation error message
		$_SESSION['validatorPassback'] = array(
			'username' => $_POST['username'],
			'password' => $_POST['password'],
			'confirmPassword' => $_POST['confirmPassword'],
			'email' => $_POST['email'],
			// Comment to stop NetBeans auto formatting them back together again
			'usernameClass' => isset($valerr['username']) ? 'error' : 'valid',
			'passwordClass' => isset($valerr['password']) ? 'error' : 'valid',
			'confirmPasswordClass' => isset($valerr['confirmPassword']) ? 'error' : 'valid',
			'emailClass' => isset($valerr['email']) ? 'error' : 'valid'
		);
		$message .= Message::validation($valerr);
	}
	$_SESSION['message'] = $message;
	redirect('/login/fromRegister', true);
}
