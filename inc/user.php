<?php

class User {
	/**
	 * @var boolean If the user is logged in then true.
	 */
	public static $isValid = false;

	/**
	 * @var string The username from the session.
	 */
	public static $uname;

	/**
	 * @var string The password hash from the session.
	 */
	public static $phash;
	
	/**
	 * @var int The role number of the user.
	 */
	public static $role;

	public static function isValid () {
		return self::$isValid;
	}

	/**
	 * Handle the login - pages/login.php
	 *
	 * @return string Any message for the form to display. Formatted with the message class.
	 */
	public static function loginHandle () {
		if(check_post('username', 'password', 'login')) {
			// for security, we don't want session fixation :(
			session_regenerate_id();

			if(self::checkSession($_POST['username'], $_POST['password'], $_SERVER['REMOTE_ADDR'])) {
				redirect('/user', true);
			}
			else {
				return Message::error("Bad username or password...");
			}
		}
	}
	
	/**
	 * Validate the registration form against acceptable usernames, passwords
	 * 
	 * @return array[string] An array of validation error messages, to be passed
	 * to Message::validation
	 */
	public static function validateRegisterForm() {
		$valerr = array();
		// Validate!
		// username first
		/* username: {
				required: true,
				username: true, (^[a-zA-Z0-9_]+$)
				rangelength: [4, 32]
			}
		*/
		if (!isset($_POST['username']) || empty($_POST['username'])) {
			$valerr['username'] = 'Please enter a username.';
		} else {
			$unameLenErr = $unameRegexErr = false;
			if (strlen($_POST['username']) < 4 || strlen($_POST['username']) > 32)  {
				//$valerr['username'] = 'Username must be between 4 and 32 characters.';
				$unameLenErr = true;
			}
			if (!ctype_alnum(str_replace('_', '', $_POST['username']))) {
				$unameRegexErr = true;
			}
			if ($unameLenErr && $unameRegexErr) {
				$valerr['username'] = 'Username must be between 4 and 32 characters and contain only letters, numbers and underscores.';
			} else if ($unameLenErr) {
				$valerr['username'] = 'Username must be between 4 and 32 characters.';
			} else if ($unameRegexErr) {
				$valerr['username'] = 'Usernames must contain only letters, numbers and underscores.';
			}
		}
		// then password
		/* password: {
				minlength: 8,
				required: true
			},
		 */
		if (!isset($_POST['password']) || empty($_POST['password'])) {
			$valerr['password'] = 'Please enter a password.';
		} else if (strlen($_POST['password']) < 8) {
			$valerr['password'] = 'Passwords must be over 8 characters long.';
		}
		// then confirmation password
		/* confirmPassword: {
				equalTo: "#passwordReg",
				required: true
			},
		 */
		if (!isset($_POST['confirmPassword']) || empty($_POST['confirmPassword']) || $_POST['confirmPassword'] !== $_POST['password']) {
			$valerr['confirmPassword'] = 'Confirmation password does not match original password.';
		}
		// now email
		/* email: {
				required: true,
				email: true
			},
		 */
		if (!isset($_POST['email']) || empty($_POST['email'])) {
			$valerr['email'] = 'Please enter a valid email address.';
		} else if (preg_match("/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i", $_POST['email'])) { // yes, this is the one jQuery uses.
			$valerr['email'] = 'Please enter a valid email address.';
		}
		// all done!
		return $valerr;
	}
	
	/**
	 * Handle the registration - pages/register.php
	 * 
	 * @return string Any message for the form to display. Formatted with the message class.
	 */
	public static function registerHandle($username, $password, $email) {
		Log::add('User::registerHandle called, beginning.');
		// Things to do:
		// Generate salt and validation key
		// Salt + pepper password
		// RUN QUERY (parameteried, duh)
		$salt = '';
		for ($i=0; $i<6; $i++) { 
			$d = rand(33,126);
			$salt .= chr($d);
		}
		$validatekey = md5($salt . $username . $email . microtime(true) . rand()); // 32 characters...
		$phash = self::hashWithSalt($password, $salt);
		
		$dbret = Database::insert('users', array('username' => $username, 'password' => $phash, 'userpwsalt' => $salt, 'email' => $email, 'validate_key' => $validatekey, 'status' => 0, 'datereg' => date('Y-m-d H:i:s')));
		Log::add('User::registerHandle, user added to database: retval = ' . $dbret);
		if ($dbret) {
			// email the user!
			global $mailer;
			$mailer->AddAddress($email, $username);
			$mailer->Subject = 'Registration at hRepo';
			$mailer->Body = <<<EOM
Hi $username!

You, or someone using your email address, from the IP address {$_SERVER['REMOTE_IP']} recently registered with hRepo (http://www.hrepo.com).

If this was you, please visit:
http://www.hrepo.com/activate/{$username}/{$validatekey}/
	
If this was not you, please visit:
http://www.hrepo.com/cancel/{$username}/{$validatekey}/
	
Thanks,
The hRepo Team
EOM;
			$mailer->WordWrap = 50;
			$mailerret = $mailer->Send();
			Log::add('User::registerHandle, sending email: retval = ' . $mailerret);
			if (!$mailerret) {
				Log::add('User::registerHandle, mailer says: ' . $mailer->ErrorInfo);
				return Message::error('An error occurred during account creation. Please contact the server admin.<br /><br /><small>ACC2</small>');
			} else {
				return Message::success('Your account has been created successfully!<br /><br />An email has been sent to allow you to verify your account. Please click the link inside to activate it.');
			}
		} else {
			Log::add('User::registerHandle, database says: ' . print_r(Database::getHandle()->errorInfo(), true));
			return Message::error('A database error occurred during account creation. Please contact the server admin.<br /><br /><small>ACC1</small>');
		}
	}

	/**
	 * Takes the cookie string and turns it into a array.
	 *
	 * @return array The assoc array of uname and pword from the cookie.
	 */
	public static function unpackCookie() {
		// cookie is in format (# of = at end)|(base64 of json uname and password assoc)
		$str = $_COOKIE['ln'];

		// no sense going further
		if(empty($str)) {
			return false;
		}

		$str = (array)explode('|', $str);

		// secert ninja stuff :)
		return json_decode(base64_decode($str[1].str_repeat('=', $str[0])));
   	}

	public static function checkCookie () {
		$res = self::unpackCookie();

		if(!$res) {
			return false;
		}

		$_SESSION['uname'] = $uname;
		$_SESSION['pword'] = $pword;
		$_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
	}

	public static function logout() {
		unset($_SESSION['uname']);
		unset($_SESSION['pword']);
		unset($_SESSION['last_ip']);
		session_destroy();
   	}

	/**
	 * Checks the validity of the session.
	 *
	 * @return boolean True if session is valid user. False otherwise. Also false if ips are different between requests.
	 */
	public static function checkSession ($uname, $pword, $last_ip, $fromsess = false) {
		$current_ip = $_SERVER['REMOTE_ADDR'];

		// session not set.
		if(empty($uname) || empty($pword)) {
			Log::add('User::checkSession called - returning false, uname/pword blank.');
			return false;
		}

		// session spoofing!!! or... an AOL user. Meh. AOL users are spammy.
		if($last_ip !== $current_ip) {
			Log::add('User::checkSession called - returning false, IPs do not match. Old IP: ' . $last_ip . ' - New IP: ' . $current_ip);
			return false;
		}
		$_SESSION['last_ip'] = $current_ip;

		$smt = Database::select('users', array('password', 'userpwsalt', 'role'), array('username = ? AND status = 1', $uname));
		$row = $smt->fetch(PDO::FETCH_ASSOC);
		
		if ($fromsess) {
			$hash = $pword;
		} else {
			$hash = self::hashWithSalt($pword, $row['userpwsalt']); // PHP does not stand for PHP: Hypertext Prediction engine. Putting this line BEFORE $row is set fails
		}

		// correct password
		if($hash === $row['password']) {
			$_SESSION['uname']  = self::$uname = $uname;
			$_SESSION['pword']  = self::$phash = $hash;
			$_SESSION['role']  = self::$role = $row['role'];
			return true;
		}
		
		Log::add('User::checkSession called - returning false, incorrect password: $hash was ' . $hash .' and correct hash was ' . $row['password']);

		// wrong password
		return false;
   	}
	
	public static function hashWithSalt ($hash, $salt) {
		return hash('whirlpool', $hash.$salt);
	}

	public static function bootstrap () {
		session_start();

		self::$isValid = self::checkSession($_SESSION['uname'], $_SESSION['pword'], $_SESSION['last_ip'], true);
		if(!self::$isValid) {
			self::checkCookie();
			self::$isValid = self::checkSession($_SESSION['uname'], $_SESSION['pword'], $_SESSION['last_ip'], true);
		}
	}
}


