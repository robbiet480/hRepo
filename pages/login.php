<?php
	if(isset($_POST['submit']))
	{
	require_once('mysql.php');
	//Check if the user supplied both required fields
	if (!$_POST['username'] | !$_POST['password'] )
	{
		die('One of the fields were not filled in.');
	}
	
	//Let us cleanse these variables
	$_POST['username'] = stripslashes($_POST['username']);
	$username = mysql_real_escape_string($_POST['username']);
	$_POST['password'] = stripslashes($_POST['password']);
	$password = mysql_real_escape_string($_POST['password']);
	
	//Encrypt the password to MD5
	$password = hash( 'whirlpool', $password );
	
	//Check against the database for the username and password
	$compare = mysql_query("SELECT * FROM users WHERE username = '".$username."'")or die(mysql_error());
	
	//Check if user exists
	$userexists = mysql_num_rows($compare);
	if ($userexists == 0) 
	{
		die('The specified user does not exist. Please register.');
	}
	
	while($userinfo = mysql_fetch_array($compare))
	{
		//Checks the password
		if ($password != $userinfo['password'])
		{
			die('Password is incorrect.');
		}
		//If login is good, set the cookie
		else
		{
			$cookietime = time() + 14400;
			setcookie("hrepouser", $username, $time);
			setcookie("hrepopass", $password, $time);
			//Now redirect to where you want the member to goto
			//Change this if needed
			header("Location: index.html");
		}
	}
	mysql_close($connection);
	}

$nav['login'] = array('url' => '/login', 'slug' => 'login', 'name' => 'Login', 'loggedInOnly' => -1, 'weight' => 4); // -1 for only not logged in
if($slug == "login") {
	Content::setContent(<<<EOT
	<form action="#" method="POST">
	<label for="username" style="font-size:12pt;font-weight:bold;">Username:</label><input style="font-size:18pt;" size="25" type="text" name="username" id="username"><br /><br />
	<label for="password" style="font-size:12pt;font-weight:bold;">Password:</label><input style="font-size:18pt;" size="25" type="password" name="password" id="password"><br />
	<input type="submit" value="Login">
	</form>


EOT
	);
	
}
