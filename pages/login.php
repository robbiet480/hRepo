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
if($slug == "login") {
	inclib('recaptchalib.php');
	Content::addAdditionalCSS('login.css');
	print_r($_SESSION);
	$message = $regMessage = '';
	if (isset($_GET['regMessage']) && $_GET['regMessage']) { // message passed over?
		$regMessage = isset($_SESSION['message']) ? $_SESSION['message'] : Message::error('Something undefined happened!');
		$_SESSION['message'] = '';
	} else {
		$message = User::loginHandle();
	}
	if (isset($_SESSION['recaperror'])) {
		$recaperror = $_SESSION['recaperror'];
	} else {
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
        <form action="/register" method="post">$regMessage
                <div class="form-row">
                        <label for="username">Username</label>
                        <span><input type="text" name="username" id="username" /></span>
                </div>
                <div class="form-row">
                        <label for="password">Password</label>
                        <span><input type="password" name="password" id="password" /></span>
                </div>
				<div class="form-row">
                        <label for="password">Confirm Password</label>
                        <span><input type="password" name="confirmpassword" id="confirmpassword" /></span>
                </div>
				<div class="form-row">
                        <label for="password">E-mail</label>
                        <span><input type="text" name="email" id="email" /></span>
                </div>
				<div class="form-row">
                        <label for="captcha">CAPTCHA</label>
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

if (!User::isValid()) {
	Content::addAdditionalJS('loginbox.js');
}
