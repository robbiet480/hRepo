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
	Content::addAdditionalCSS('login.css');
	$message = User::loginHandle();
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
        <form action="/register" method="post">
                <div class="form-row">
                        <label for="username">Username</label>
                        <span><input type="text" name="username" id="username" /></span>
                </div>
                <div class="form-row">
                        <label for="password">Password</label>
                        <span><input type="password" name="password" id="password" /></span>
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
