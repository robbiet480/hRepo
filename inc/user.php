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
	/**
	 * @var XenForo_Visitor The visitor object!
	 */
	public static $visitor;

	public static function isValid() {
		return self::$isValid;
	}

	public static function bootstrap() {
		// You can fetch user data using the visitor object
		$visitor = XenForo_Visitor::getInstance();
		
		self::$uname = $visitor->get('username');
		self::$uid = $visitor->getUserId();
		self::$visitor = $visitor;
		
		// ROLE CALCULATION
		// -1 = guest, 0 = member, 1 = dev, 2 = admin
		if (self::$uid == 0 || $visitor->get('is_banned')) {
			// guest:
			self::$role = -1;
			self::$isValid = false;
		} else {
			self::$isValid = true;
			if ($visitor->get('is_admin'))
				self::$role = 2;
			else
				self::$role = 0;
		}
		
		// TODO: implement dev
		
	}

}

