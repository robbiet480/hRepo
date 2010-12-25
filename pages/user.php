<?php

$nav['user'] = array('url' => '/user', 'slug' => 'user', 'name' => 'Profile', 'loggedInOnly' => 1, 'weight' => 3, 'extrapre' => '', 'extrapost' => ''); // -1 for only not logged in

if ($slug == 'user' && !User::$isValid)
{
	$httpError = 403;
}
else if ($slug == 'user')
{

	if ($params[0] == 'edit')
	{
		// have they submitted?
		if (isset($_POST['submit']))
		{ // yes
			// handle form submission
		}
		$uname = User::$uname;
		$pword = 'oloyoudidntthinkweactuallystorethepassworddidyou';
		$email = User::getField('email');
		// display the form
		Content::setContent(<<<EOT
	<h1>Edit your profile</h1>
	<form action="/user/edit" method="POST">
		<div class="form-row">
			<label for="username">Username</label>
			<span><input type="text" name="username" id="username" value="$uname" disabled="disabled" /></span>
		</div>
		<div class="form-row">
			<label for="password">Password</label>
			<span><input type="password" name="password" id="password" value="$pword" /></span>
		</div>
		<div class="form-row">
			<label for="email">Email</label>
			<span><input type="text" name="email" id="email" value="$email" /></span>
		</div>
		<div class="form-row form-row-last">
			<span><input type="submit" name="submit" value="Edit Profile" /></span>
		</div>
	</form>
EOT
		);
	}
	else if ($params[0] == 'requestdev')
	{
		if (User::getField('wantsdev'))
		{
			$message = Message::error('Your request has already been sent to the administrators for approval.');
			Content::setContent(<<<EOT
					<h1>Request Developer Access</h1>
					<p>$message</p>
EOT
			);
		}
		else if ($params[1] != 'yesimsure')
		{
			Content::setContent(<<<EOT
				<h1>Request Developer Access</h1>
				<p>Are you sure you want to do this? <form action="/user/requestdev/yesimsure/" method="POST"><input type="submit" value="Yes, I'm sure!" /></form></p>
EOT
			);
		}
		else
		{
			if (Database::update('users', array('wantsdev' => 1), null, array('uid = ?', User::$uid)))
			{
				$success = Message::success('The website administrators have been sent an approval request.');
			}
			else
			{
				$success = Message::error('A database error occurred whilst attempting to send the approval request.<br />Please try later.');
			}
			Content::setContent(<<<EOT
				<h1>Request Developer Access</h1>
				<p>$success</p>
EOT
			);
		}
	}
	else
	{
		$uname = User::$uname;
		$email = User::getField('email');
		switch (User::$role)
		{
			case 0:
				$axx = 'Member';
				break;
			case 1:
				$axx = 'Developer';
				break;
			case 2:
				$axx = 'Administrator :)';
				break;
			default:
				$axx = ':/';
				break;
		}
		// Welcome!
		Content::setContent(<<<EOT
<h1>Welcome to your user page!</h1>
<h2>User Details</h2>
<dl class="userdets">

<dt>Username</dt>
<dd>$uname</dd>

<dt>Email Address</dt>
<dd>$email</dd>
	
<dt>Access Level</dt>
<dd>$axx</dd>
</dl>
EOT
		);
	}
	Sidebar::clear();
	if (User::$role == 0 && !User::getField('wantsdev'))
	{
		$requestpermissions = '<li><a href="/user/requestdev">Request Developer Access</a></li>';
	}
	else if (User::getField('wantsdev'))
	{
		$requestpermissions = '<li>Developer Access Requested</li>';
	}
	Sidebar::add('User CP', <<<EOT
	<ol>
		<li><a href="/user/edit">Edit Profile</a></li>
		$requestpermissions
	</ol>
EOT
	);
}
