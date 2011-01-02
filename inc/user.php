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
	
	const ROLE_ADMIN = 2;
	const ROLE_DEVELOPER = 1;
	const ROLE_MEMBER = 0;
	const ROLE_GUEST = -1;
	
	/**
	 * @var int The role number of the user.
	 * 
	 * -1 = Guest
	 * 0 = Member
	 * 1 = Developer
	 * 2 = Administrator
	 * 
	 */
	public static $role = self::ROLE_GUEST;

	
	/**
	 * @var XenForo_Visitor The visitor object!
	 */
	public static $visitor;

	public static function isValid() {
		return self::$isValid;
	}

	public static function bootstrap() {
		session_start();
		if (!isset($_GET[session_id()])) {
			// You can fetch user data using the visitor object
			$visitor = XenForo_Visitor::getInstance();
			
			$_SESSION['uname'] = self::$uname = $visitor->get('username');
			$_SESSION['uid'] = self::$uid = $visitor->getUserId();
			$_SESSION['visitor'] = self::$visitor = $visitor;
			
			// ROLE CALCULATION
			// -1 = guest, 0 = member, 1 = dev, 2 = admin
			if (self::$uid == 0 || $visitor->get('is_banned')) {
				// guest:
				self::$role = self::ROLE_GUEST;
				self::$isValid = false;
			} else {
				self::$isValid = true;
				if ($visitor->get('is_admin'))
					self::$role = self::ROLE_ADMIN;
				else
					self::$role = self::ROLE_MEMBER;
			}
			$_SESSION['role'] = self::$role;
			$_SESSION['isValid'] = self::$isValid;
		} else {
			self::$uname = $_SESSION['uname'];
			self::$uid = $_SESSION['uid'];
			self::$visitor = $_SESSION['visitor'];
			self::$role = $_SESSION['role'];
			self::$isValid = $_SESSION['isValid'];
		}
		
		// TODO: implement dev
		
	}

}

