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
	public static function validation ($reasons) {
		$valcomb = '';
		foreach ($reasons as $reason) {
			$valcomb .= '<li>'.$reason.'</li>';
		}
		return "<div class='message message-validation'>Validation failed:<ul>$valcomb</ul></div>";
	}
}
