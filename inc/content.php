<?php

class Content {
	public static $content;
	public static $headers = array(
		'Content-Type' => 'text/html; charset=utf-8'
	);
	public static $useTemplate = true;
	public static $status = "200 OK";
	
	public static function set($content) {
		self::$content = $content;
	}
	
	public static function setContent($x) {
		return self::set($x);
	}
	
	public static function append($x) {
		self::$content .= $x;
	}
	
	public static function prepend($x) {
		self::$content = $x.self::$content;
	}
	
	public static function setHeader($k,$v) {
		self::$headers[$k] = $v;
	}
}