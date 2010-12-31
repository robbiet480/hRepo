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
	 * @var integer The uid from the database from the session.
	 */
	public static $uid;
	/**
	 * @var int The role number of the user.
	 * 
	 * -1 = Guest
	 * 0 = Member
	 * 1 = Developer
	 * 2 = Administrator
	 * 
	 */
	public static $role = -1;

	public static function isValid() {
		return self::$isValid;
	}

	/**
	 * Handle the login - pages/login.php
	 *
	 * @return string Any message for the form to display. Formatted with the message class.
	 */
	public static function loginHandle() {
		if (check_post('username', 'password', 'login'))
		{
			// for security, we don't want session fixation :(
			session_regenerate_id();

			if (self::checkSession($_POST['username'], $_POST['password'], $_SERVER['REMOTE_ADDR']))
			{
				if ($_POST['rememberMe'] == 'rememberMeFoSure')
				{
					Log::add('Woo, I need to remember them...');
					self::setRememberMe();
				}
				redirect('/user', true); // commented out for debugging
			}
			else
			{
				return Message::error("Bad username or password...");
			}
		}
	}

	/**
	 * Get a database field!
	 * 
	 * @return mixed Whatever comes back from the database
	 */
	public static function getField($fieldname) {
		$out = '';
		$pdd = Database::select('users', array($fieldname), array('uid = ?', self::$uid));
		if ($pdd->rowCount() == 0)
			return 'ERROR ERROR ERROR';
		$out = $pdd->fetchColumn();
		return $out;
	}

	public static function validateUsername($uname) {
		$unameLenErr = $unameRegexErr = false;
		$valerr = true;
		if (strlen($_POST['username']) < 4 || strlen($_POST['username']) > 32)
		{
			//$valerr['username'] = 'Username must be between 4 and 32 characters.';
			$unameLenErr = true;
		}
		if (!ctype_alnum(str_replace('_', '', $_POST['username'])))
		{
			$unameRegexErr = true;
		}
		if ($unameLenErr && $unameRegexErr)
		{
			$valerr = 'Username must be between 4 and 32 characters and contain only letters, numbers and underscores.';
		}
		else if ($unameLenErr)
		{
			$valerr = 'Username must be between 4 and 32 characters.';
		}
		else if ($unameRegexErr)
		{
			$valerr = 'Usernames must contain only letters, numbers and underscores.';
		}
		// Is already taken?
		$pds = Database::select('users', array('uid'), array('username = ?', $_POST['username']));
		if ($pds->rowCount() != 0)
		{
			$valerr = 'Username is already taken. :(';
		}
		return $valerr;
	}

	/**
	 * Validate the registration form against acceptable usernames, passwords
	 * 
	 * @return array[string] An array of validation error messages, to be passed
	 * to Message::validation
	 */
	public static function validateRegisterForm() {
		Log::add('User::validateRegisterForm - beginning validation.');
		$valerr = array();
		// Validate!
		// username first
		/* username: {
		  required: true,
		  username: true, (^[a-zA-Z0-9_]+$)
		  rangelength: [4, 32]
		  }
		 */
		if (!isset($_POST['username']) || empty($_POST['username']))
		{
			$valerr['username'] = 'Please enter a username.';
		}
		else
		{
			$valerr['username'] = self::validateUsername($_POST['username']);
			if ($valerr['username'] == true)
				unset($valerr['username']);
		}
		Log::add('User::validateRegisterForm - username check complete: ' . print_r($valerr, true));
		// then password
		/* password: {
		  minlength: 8,
		  required: true
		  },
		 */
		if (!isset($_POST['password']) || empty($_POST['password']))
		{
			$valerr['password'] = 'Please enter a password.';
		}
		else if (strlen($_POST['password']) < 8)
		{
			$valerr['password'] = 'Passwords must be over 8 characters long.';
		}
		Log::add('User::validateRegisterForm - password check complete: ' . print_r($valerr, true));
		// then confirmation password
		/* confirmPassword: {
		  equalTo: "#passwordReg",
		  required: true
		  },
		 */
		if (!isset($_POST['confirmPassword']) || empty($_POST['confirmPassword']) || $_POST['confirmPassword'] !== $_POST['password'])
		{
			$valerr['confirmPassword'] = 'Confirmation password does not match original password.';
		}
		Log::add('User::validateRegisterForm - double password check complete: ' . print_r($valerr, true));
		// now email
		/* email: {
		  required: true,
		  email: true
		  },
		 */
		if (!isset($_POST['email']) || empty($_POST['email']))
		{
			$valerr['email'] = 'Please enter a valid email address.';
		}
		else if (!self::validEmail($_POST['email']))
		{ // yes, this is the one jQuery uses.
			$valerr['email'] = 'Please enter a valid email address.';
		}
		Log::add('User::validateRegisterForm - email check complete - validation complete: ' . print_r($valerr, true));
		// all done!
		return $valerr;
	}

	/**
	  Validate an email address.
	  Provide email address (raw input)
	  Returns true if the email address has the email
	  address format and the domain exists.
	 * 
	 * http://www.linuxjournal.com/article/9585?page=0,3
	 */
	public static function validEmail($email) {
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex)
		{
			$isValid = false;
		}
		else
		{
			$domain = substr($email, $atIndex + 1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64)
			{
				// local part length exceeded
				$isValid = false;
			}
			else if ($domainLen < 1 || $domainLen > 255)
			{
				// domain part length exceeded
				$isValid = false;
			}
			else if ($local[0] == '.' || $local[$localLen - 1] == '.')
			{
				// local part starts or ends with '.'
				$isValid = false;
			}
			else if (preg_match('/\\.\\./', $local))
			{
				// local part has two consecutive dots
				$isValid = false;
			}
			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			{
				// character not valid in domain part
				$isValid = false;
			}
			else if (preg_match('/\\.\\./', $domain))
			{
				// domain part has two consecutive dots
				$isValid = false;
			}
			else if
			(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local)))
			{
				// character not valid in local part unless 
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local)))
				{
					$isValid = false;
				}
			}
			if ($isValid && !(checkdnsrr($domain, "MX") ||
					checkdnsrr($domain ,"A")))
			{
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
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
		for ($i = 0; $i < 6; $i++)
		{
			$d = rand(33, 126);
			$salt .= chr($d);
		}
		$validatekey = md5($salt . $username . $email . microtime(true) . rand()); // 32 characters...
		$phash = self::hashWithSalt($password, $salt);

		$dbret = Database::insert('users', array('username' => $username, 'password' => $phash, 'userpwsalt' => $salt, 'email' => $email, 'validate_key' => $validatekey, 'status' => 0, 'datereg' => date('Y-m-d H:i:s')));
		Log::add('User::registerHandle, user added to database: retval = ' . $dbret);
		if ($dbret)
		{
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
			if (!$mailerret)
			{
				Log::add('User::registerHandle, mailer says: ' . $mailer->ErrorInfo);
				return Message::error('An error occurred during account creation. Please contact the server admin.<br /><br /><small>ACC2 - '.$mailer->ErrorInfo.'</small>');
			}
			else
			{
				return Message::success('Your account has been created successfully!<br /><br />An email has been sent to allow you to verify your account. Please click the link inside to activate it.');
			}
		}
		else
		{
			Log::add('User::registerHandle, database says: ' . print_r(Database::getHandle()->errorInfo(), true));
			return Message::error('A database error occurred during account creation. Please contact the server admin.<br /><br /><small>ACC1</small>');
		}
	}

	/**
	 * Set a long lived cookie, for remember me!
	 * 
	 * @return void
	 */
	public static function setLongCookie($cookiename, $cookiedata) {
		setcookie($cookiename, $cookiedata, time() + 60 * 60 * 24 * 356 /* expire in a year */);
	}

	public static function getIV() {
		if (empty($_COOKIE['ln_iv']) || !($iv = (base64_decode($_COOKIE['ln_iv'], false))))
		{
			Log::add('User::getIV - Generating new IV');
			srand((double) (microtime(true) * 2125000)); // seed PRNG
			$tr = mcrypt_module_open(MCRYPT_BLOWFISH, '', MCRYPT_MODE_CBC, ''); // Start up mcrypt...
			$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($tr), MCRYPT_RAND);
			self::setLongCookie('ln_iv', base64_encode($iv));
			return $iv;
		}
		else
		{
			Log::add('User::getIV - return IV from cookie');
			return $iv;
		}
	}

	public static function setRememberMe() {
		// Set up an IV
		$iv = self::getIV();
		// Set cookie data
		$cookiedat = array('uname' => self::$uname, 'pword' => self::$phash);
		// Now set up the actual cookie
		$cookiedata = base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, HR_BLOWFISH_SECRET, json_encode($cookiedat), MCRYPT_MODE_CBC, $iv));
		self::setLongCookie('ln', $cookiedata);
	}

	/**
	 * Takes the cookie string and turns it into a array.
	 *
	 * @return array The assoc array of uname and pword from the cookie.
	 */
	public static function unpackCookie() {
		// cookie is in format json uname and password assoc
		$str = trim(mcrypt_decrypt(MCRYPT_BLOWFISH, HR_BLOWFISH_SECRET, base64_decode($_COOKIE['ln'], true), MCRYPT_MODE_CBC, self::getIV()));

		// no sense going further
		if (empty($str))
		{
			return false;
		}

		// secert ninja stuff :)
		return json_decode($str);
	}

	public static function checkCookie() {
		$res = self::unpackCookie();

		if (!$res)
		{
			return false;
		}

		$_SESSION['uname'] = $res->uname;
		$_SESSION['pword'] = $res->pword;
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
	public static function checkSession($uname, $pword, $last_ip, $fromsess = false) {
		$current_ip = $_SERVER['REMOTE_ADDR'];

		// session not set.
		if (empty($uname) || empty($pword))
		{
			Log::add('User::checkSession called - returning false, uname/pword blank.');
			return false;
		}

		// session spoofing!!! or... an AOL user. Meh. AOL users are spammy.
		if ($last_ip !== $current_ip)
		{
			Log::add('User::checkSession called - returning false, IPs do not match. Old IP: ' . $last_ip . ' - New IP: ' . $current_ip);
			return false;
		}
		$_SESSION['last_ip'] = $current_ip;

		$smt = Database::select('users', array('password', 'userpwsalt', 'role', 'uid'), array('username = ? AND status = 1', $uname));
		$row = $smt->fetch(PDO::FETCH_ASSOC);

		if ($fromsess)
		{
			$hash = $pword;
		}
		else
		{
			$hash = self::hashWithSalt($pword, $row['userpwsalt']); // PHP does not stand for PHP: Hypertext Prediction engine. Putting this line BEFORE $row is set fails
		}

		// correct password
		if ($hash === $row['password'])
		{
			$_SESSION['uname'] = self::$uname = $uname;
			$_SESSION['pword'] = self::$phash = $hash;
			$_SESSION['role'] = self::$role = $row['role'];
			$_SESSION['uid'] = self::$uid = $row['uid'];
			return true;
		}

		Log::add('User::checkSession called - returning false, incorrect password: $hash was ' . $hash . ' and correct hash was ' . $row['password']);

		// wrong password
		return false;
	}

	public static function hashWithSalt($hash, $salt) {
		return hash('whirlpool', $hash . $salt);
	}

	public static function bootstrap() {
		session_start();

		self::$isValid = self::checkSession($_SESSION['uname'], $_SESSION['pword'], $_SESSION['last_ip'], true);
		if (!self::$isValid)
		{
			self::checkCookie();
			self::$isValid = self::checkSession($_SESSION['uname'], $_SESSION['pword'], $_SESSION['last_ip'], true);
		}
	}

}

