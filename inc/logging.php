<?php
/**
 * This class is useful for logging things. Use ::add to add a message, the microtime will be associated with it.
 *
 * This log lasts the duration of the request.
 */
class Log {
	private static $log;

	/**
	 * Add a message to the log.
	 *
	 * @param mixed $val Something to be logged with the microtime attached to it.
	 */
	public static function add($val) {
		self::$log[] = array(
			'time' => microtime(true),
			'val' => $val
		);
	}

	/**
	 * Gets the raw log.
	 *
	 * @return array An array of all of the things logged. Each item looks like this: array('time'=> 1231203.2322, 'val' => 'some log val') .
	 */
	public static function getLog() {
		return self::$log;
	}
}

Log::add('Logging library loaded!');