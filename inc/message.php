<?php

class Message {
	public static function error ($message) {
		return "<div class='message message-error'><p>$message</p></div>";
	}
	public static function success ($message) {
		return "<div class='message message-success'><p>$message</p></div>";
	}
	public static function notice ($message) {
		return "<div class='message message-notice'><p>$message</p></div>";
	}
	public static function warning ($message) {
		return "<div class='message message-warning'><p>$message</p></div>";
	}
	public static function validation ($message) {
		return "<div class='message message-validation'><p>$message</p></div>";
	}
}
