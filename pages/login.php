<?php

$logindropdown = <<<EOT
<div id="loginDropDown">
        <form action="/login" method="post">
                <label for="usernameBox">Username:</label>
                <input type="text" name="username" id="usernameBox" /><br />

                <label for="passwordBox">Password:</label>
                <input type="password" name="password" id="passwordBox" /><br />

                <span id="loginBoxRMe">
                        <label for="rememberMeBox">Remember Me</label>
                        <input type="checkbox" name="rememberMe" id="rememberMeBox" />
                </span>

                <span id="loginBoxLogin">
                        <input type="submit" name="login" value="Login" />
                </span>
				
				<br />
				
				<a href="/login" id="loginBoxRegister">Need to register?</a>
        </form>
</div>
EOT;


$nav['login'] = array('url' => '/login', 'slug' => 'login', 'name' => 'Login', 'loggedInOnly' => -1, 'weight' => 4, 'extrapre' => $logindropdown, 'extrapost' => ''); // -1 for only not logged in
if ($slug == "login")
{
	inclib('recaptchalib.php');
	Content::addAdditionalCSS('login.css');
	Content::addAdditionalJS('registervalidate.js');
	Content::addAdditionalJS('//dev.jquery.com/view/trunk/plugins/metadata/jquery.metadata.js'); // Yes, // is valid
	Content::addAdditionalJS('//ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.pack.js');
	Content::addAdditionalJS('//ajax.microsoft.com/ajax/jquery.validate/1.7/additional-methods.js');

	$message = $regMessage = '';
	if (isset($params[0]) && $params[0] == 'fromRegister')
	{ // message passed over?
		if (isset($_SESSION['validatorPassback'])) {
			$username = $password = $confirmPassword = $email = $usernameClass = $passwordClass = $confirmPasswordClass = $emailClass = '';
			$vars = array('username', 'password', 'confirmPassword', 'email', 'usernameClass', 'passwordClass', 'confirmPasswordClass', 'emailClass');
			foreach ($vars as $var) {
				$$var = $_SESSION['validatorPassback'][$var];
			}
		}
		$regMessage = isset($_SESSION['message']) ? $_SESSION['message'] : Message::error('Something undefined happened!');
		unset($_SESSION['message']);
	}
	else
	{
		$username = $password = $confirmPassword = $email = $usernameClass = $passwordClass = $confirmPasswordClass = $emailClass = '';
		$message = User::loginHandle();
	}
	if (isset($_SESSION['recaperror']))
	{
		$recaperror = $_SESSION['recaperror'];
		unset($_SESSION['recaperror']);
	}
	else
	{
		$recaperror = null;
	}
	$recaptcha = recaptcha_get_html($recaperror);
	Content::setContent(<<<EOT
	<h1>Login</h1>
<div id="loginHalf">
	<h4>Login to hRepo</h4>
	<form action="/login" method="post">$message
		<div class="form-row">
			<label for="username">Username</label>
			<span><input type="text" name="username" id="username" /></span>
		</div>
		<div class="form-row">
			<label for="password">Password</label>
			<span><input type="password" name="password" id="password" /></span>
		</div>
		<div class="form-row form-row-last">
			<span><input type="submit" name="login" value="Login" /></span>
		</div>
	</form>
</div>
<div id="registerHalf">
	<h4>Need to register?</h4>
        <form action="/register" method="post" id="registerForm">
			<div id="notificationArea">$regMessage</div>

                <div class="form-row">
                        <label for="usernameReg">Username</label>
                        <span><input type="text" name="username" id="usernameReg" value="$username" class="$usernameClass" /></span>
                </div>

                <div class="form-row">
                        <label for="passwordReg">Password</label>
                        <span><input type="password" name="password" id="passwordReg" value="$password" class="$passwordClass" /></span>
                </div>

				<div class="form-row">
                        <label for="confirmPasswordReg">Confirm Password</label>
                        <span><input type="password" name="confirmPassword" id="confirmPasswordReg" value="$confirmPassword" class="$confirmPasswordClass" /></span>
                </div>

				<div class="form-row">
                        <label for="emailReg">E-mail</label>
                        <span><input type="text" name="email" id="emailReg" value="$email" class="$emailClass" /></span>
                </div>
			
				<div class="form-row">
                        <span>$recaptcha</span>
                </div>
				
                <div class="form-row form-row-last">
                        <span><input type="submit" name="login" value="Register!" /></span>
                </div>
        </form>
	
</div>


EOT
	);
}

if (!User::isValid())
{
	Content::addAdditionalJS('loginbox.js');
}
