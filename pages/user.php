<?php

$nav['user'] = array('url' => '/user', 'slug' => 'user', 'name' => 'Profile', 'loggedInOnly' => 1, 'weight' => 3, 'extrapre' => '', 'extrapost' => ''); // -1 for only not logged in

if ($slug == 'user')
{
	$pword = 'oloyoudidntthinkweactuallystorethepassworddidyou';
	if (!isset($params[0]) && User::isValid())
	{
		redirect('/user/' . User::$uname, true);
		exit();
	}
	else if (!isset($params[0]) && !User::isValid())
	{
		// user is not logged in
		Content::setContent('<h1>Profile error</h1>' . Message::error('Please specify a username.'));
	}
	else
	{
		// Ugh, database grab
		$a = Database::select('users', '*', array('username = ?', $params[0]));
		if (!$a->rowCount())
		{
			Content::setContent('<h1>' . $params[0] . '\'s profile</h1>' . Message::error('No such user exists.'));
		}
		else
		{
			$b = $a->fetch(PDO::FETCH_ASSOC);
			$uname = $b['username'];
			$email = $b['email'];
			$status = $b['status'];
			$datereg = strtotime($b['datereg']);
			$urole = $b['role'];
			$usalt = $b['userpwsalt'];

			if ($params[1] == 'edit' && User::$role > 1)
			{
				// have they submitted?
				if (isset($_POST['submit']))
				{ // yes
					// handle form submission
					$changesArray = array();
					$valerr = array();

					// password
					if ($_POST['password'] != $pword)
					{
						if (strlen($_POST['password']) < 8)
						{
							$valerr['password'] = 'Passwords must be longer than 8 characters.';
						}
						else
						{
							// has changed
							$changesArray['password'] = User::hashWithSalt($_POST['password'], $usalt);
						}
					}

					// email
					if ($_POST['email'] != $email)
					{
						if (!User::validEmail($_POST['email']))
						{
							$valerr['email'] = 'Please enter a valid email address.';
						}
						else
						{
							$changesArray['email'] = $_POST['email'];
							$email = $_POST['email'];
						}
					}

					if (User::$role == 2)
					{ // if is an administrator...
						// can edit more stuff!
						// username
						if ($_POST['username'] != $uname && $_POST['username'] != '')
						{
							$valerra = User::validateUsername($uname);
							if ($valerra == '')
							{
								$changesArray['username'] = $_POST['username'];
								$uname = $_POST['username'];
							}
							else
							{
								$valerr['username'] = $valerra;
							}
						}

						// user role
						if ($_POST['role'] != $urole && is_numeric($_POST['role']) && $_POST['role'] >= 0 && $_POST['role'] <= 2)
						{
							$changesArray['role'] = $_POST['role'];
							$urole = $_POST['role'];
						}
					}
					if (count($changesArray) == 0)
					{
						// fail
						$message .= Message::error('You didn\'t specify any valid changes!');
					}
					else
					{
						if (Database::update('users', $changesArray))
						{
							$message .= Message::success('Profile updated!');
						}
						else
						{
							$message .= Message::error('A database error occurred during the profile update.');
						}
					}
				}

				if ($uname == User::$uname)
				{
					$unamebit = 'your';
					$classbit = '';
					$changeUn = 'disabled="disabled"';
				}
				else
				{
					$unamebit = $uname . '\'s';
					$isclass = array('', '', '');
					$isclass[$urole] = ' selected="selected"';
					$changeUn = '';
					$classbit = <<<EOT
		<div class="form-row">
			<label for="role">Role</label>
			<span><select name="role" id="role">
				<option value="0"{$isclass[0]}>Member</option>
				<option value="1"{$isclass[1]}>Developer</option>
				<option value="2"{$isclass[2]}>Administrator</option>
			</select></span>
		</div>
EOT;
				}

				// display the form
				Content::setContent(<<<EOT
	<h1>Edit $unamebit profile</h1>
	<form action="/user/$uname/edit" method="POST">
		<div class="form-row">
			<label for="username">Username</label>
			<span><input type="text" name="username" id="username" value="$uname" $changeUn /></span>
		</div>
		<div class="form-row">
			<label for="password">Password</label>
			<span><input type="password" name="password" id="password" value="$pword" /></span>
		</div>
		<div class="form-row">
			<label for="email">Email</label>
			<span><input type="text" name="email" id="email" value="$email" /></span>
		</div>
		$classbit
		<div class="form-row form-row-last">
			<span><input type="submit" name="submit" value="Edit Profile" /></span>
		</div>
	</form>
EOT
				);
			}
			else if ($params[1] == 'requestdev' && $params[0] == User::$uname)
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
				else if ($params[2] != 'yesimsure')
				{
					Content::setContent(<<<EOT
				<h1>Request Developer Access</h1>
				<p>Are you sure you want to do this? <form action="/user/$uname/requestdev/yesimsure/" method="POST"><input type="submit" value="Yes, I'm sure!" /></form></p>
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
				switch ($urole)
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
				switch ($status)
				{
					case 0:
						$statustext = 'Awaiting activation';
						break;
					case 1:
						$statustext = 'Active';
						break;
					case 2:
						$statustext = 'Banned';
						break;
					default:
						$statustext = ':/';
						break;
				}
				$regdatetext = date('jS \o\f F, Y', $datereg);
				// Welcome!
				Content::setContent(<<<EOT
<h1>$uname's profile</h1>
<h2>User Details</h2>
<dl class="userdets">

<dt>Username</dt>
<dd>$uname</dd>

<dt>Email Address</dt>
<dd>$email</dd>
	
<dt>Access Level</dt>
<dd>$axx</dd>
	
<dt>Status</dt>
<dd>$statustext</dd>
	
<dt>Date Registered</dt>
<dd>$regdatetext</dd>
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
			if (User::$uname == $uname || User::$role > 1)
			{
				Sidebar::add('User CP', <<<EOT
	<ol>
		<li><a href="/user/$uname/edit">Edit Profile</a></li>
		$requestpermissions
	</ol>
EOT
				);
			}
		}
	}
}
