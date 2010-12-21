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
	public static function checkSession ($uname, $pword, $last_ip) {
		$current_ip = $_SERVER['REMOTE_ADDR'];
		$hash = self::hashWithSalt($pword, $row['userpwsalt']);

		// session not set.
		if(empty($uname) || empty($pword)) {
			return false;
		}

		// session spoofing!!! or... an AOL user. Meh. AOL users are spammy.
		if($last_ip !== $current_ip) {
			return false;
		}
		$_SESSION['last_ip'] = $current_ip;

		$smt = Database::select('users', array('password', 'userpwsalt', 'role'), array('username = ?', $uname));
		$row = $smt->fetch(PDO::FETCH_ASSOC);

		// correct password
		if($hash === $row['password']) {
			$_SESSION['uname']  = $uname;
			$_SESSION['pword']  = $hash;
			$_SESSION['role']  = $row['role'];
			return true;
		}

		// wrong password
		return false;
   	}
	
	public static function hashWithSalt ($hash, $salt) {
		return hash('whirlpool', $hash.$salt);
	}

	public static function bootstrap () {
		session_start();

		self::$isValid = Users::checkSession($_SESSION['uname'], $_SESSION['pword'], $_SESSION['last_ip']);
		if(!self::$isValid) {
			Users::checkCookie();
			self::$isValid = Users::checkSession($_SESSION['uname'], $_SESSION['pword'], $_SESSION['last_ip']);
		}
	}
}


