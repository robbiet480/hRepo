<?php

class Database {

	/**
	 * @var PDOObject The handle to the database.
	 */
	private static $handle;

	/**
	 * Returns the PDOObject handle
	 *
	 * @returns PDOOBject The handle to the database.
	 */
	public static function getHandle() {
		return self::$handle;
	}

	/**
	 * FOR INTERNAL USE ONLY.
	 */
	public static function setHandle($handle) {
		self::$handle = $handle;
	}

	public static $totalTime = 0;

	public static function beginTransaction() {
		self::$handle->beginTransaction();
	}

	public static function endTransaction() {
		self::$handle->commit();
	}

	public static function rollbackTransaction() {
		self::$handle->rollBack();
	}

	private static function databaseIsntEnabledYouNoob() {
		echo 'So guess what? THE DATABASE ISN\'T ENABLED AND YOU TRIED TO CALL A DATABASE BASED FUNCTION!<br /><br />I mean seriously? Who does that.';
		die();
	}

	/**
	 * Perform a select query on the database.
	 *
	 * This will perform a select query on $table (the table prefix is automatically prefixed) and returns the specified $cols where $where is true. The arguments in $where are automatically escaped properly. Example:
	 * <code>
	 * // DB is an alias for Database
	 * DB::select('settings', array('name', 'data'), array('id > ?', 3));
	 * </code>
	 *
	 * @param string $table The table to access. The database prefix is automatically added.
	 * @param mixed $cols An array of the columns to return, or '*' for all of them. Optional if $cols is associative.
	 * @param array $where An array where the first item is one or more comparision statements. Question marks (?) can optionally be used for binding. Then the second item and beyond are the values for the binds. These binds are automatically escaped for security. Example: array('`col1' > ? AND `col2` < ?', '12', '24');
	 * @param array $order An array where the first item is a column and the second is the direction to sort (desc or asc).
	 * @param int $limit The limit on the number of results to return. Sanizied for your convience.
	 * @return PDOStatement A PDOStatement with all of the results.
	 */
	public static function select($table, $cols = '*', $where = null, $order = null, $limit = -1) {
		if (!HR_DB_ENABLE)
			self::databaseIsntEnabledYouNoob();
		$start = microtime(true);

		$prepare = array();

		$sql = "SELECT %s from `%s%s` %s %s %s";

		// $cols is like array('col1', 'col2');
		if (is_array($cols))
		{
			$cols = '`' . implode('`,`', $cols) . '`';
		}

		// using where statement
		// should be like array('`col1' > ? AND `col2` < ?', '12', '24');
		if (is_array($where))
		{
			if (count($where) > 1)
			{
				$prepare = array_slice($where, 1);
			}
			$where = 'WHERE ' . $where[0];
		}
		elseif (!is_null($where))
		{
			$where = 'WHERE ' . $where;
		}

		if (is_array($order))
		{
			$orders = "ORDER BY";
			foreach ($order as $k => $v)
			{
				if (($k + 2) % 2 == 0)
					$orders .= " `$v`";
				else
					$orders .= " $v,";
			}
			$orders = trim($orders, ',');
		}
		else
		{
			$orders = '';
		}
		$order = $orders;

		if ($limit > 0 && is_numeric($limit))
		{
			$limit = "LIMIT " . $limit;
		}
		else
		{
			$limit = '';
		}

		$sql = trim(sprintf($sql, $cols, HR_DB_PREFIX, $table, $where, $order, $limit));

		$time = microtime(true) - $start;
		self::$totalTime += $time;

		Log::add('DB Query: (time: ' . $time . ') ' . $sql);
		$smt = self::getHandle()->prepare($sql);

		if (!$smt)
		{
			print_r(self::getHandle()->errorInfo());
			return false;
		}

		if ($smt->execute($prepare))
		{
			return $smt;
		}
		else
		{
			if (HR_DB_DEBUG)
			{
				echo '<h1>DB Error:</h1>';
				print_r(self::getHandle()->errorInfo());
				echo "<h2>SQL Statement</h2><pre>$sql</pre>";
				die();
			}
			else
			{
				// OH NOES ¬_¬
				die('A database error occurred whilst processing this page.
					Please contact the site administrator!');
			}
		}
	}

	/**
	 * Perform a insert query on the database.
	 *
	 * This will perform a insert query on $table (the table prefix is automatically prefixed) and returns the number of affected rows.
	 * <code>
	 * // DB is an alias for Database
	 * DB::insert('settings', array('package', 'name', 'data'), array('the package', 'the name', 'the data'));
	 *
	 * // same as above
	 * DB::insert('settings', array('package' => 'the package', 'name' => 'the name', 'data' => 'the data'));
	 * </code>
	 *
	 * @param string $table The table to access. The database prefix is automatically added.
	 * @param array $cols Either the list of columns, or an associative array in the form `column => value`.
	 * @param array $values The respective values for $cols. The data is automatically sanitized. Optional if $cols is associative.
	 * @return int The number of rows affected.
	 */
	public static function insert($table, $cols, $values = null) {
		if (!HR_DB_ENABLE)
			self::databaseIsntEnabledYouNoob();
		$start = microtime(true);
		$prepare = array();

		$sql = "INSERT INTO `%s%s` (%s) VALUES (%s)";

		if ($values === null)
		{
			$keys = '`' . implode('`,`', array_keys($cols)) . '`';
			$binds = array_values($cols);
			$values = array_fill(0, count($binds), '?');
		}
		else
		{
			$keys = '`' . implode('`,`', array_values($cols)) . '`';
			$binds = $values;
			$values = array_fill(0, count($binds), '?');
		}

		$sql = trim(sprintf($sql, HR_DB_PREFIX, $table, $keys, implode(',', $values)));
		$time = microtime(true) - $start;
		self::$totalTime += $time;

		Log::add('DB Query: (time: ' . $time . ') ' . $sql);
		$smt = self::getHandle()->prepare($sql);

		if (!$smt)
		{
			print_r(self::getHandle()->errorInfo());
			return false;
		}

		if ($smt->execute($binds))
		{
			return $smt->rowCount();
		}
		else
		{
			if (HR_DB_DEBUG)
			{
				echo '<h1>DB Error:</h1>';
				print_r(self::getHandle()->errorInfo());
				echo "<h2>SQL Statement</h2><pre>$sql</pre>";
				die();
			}
			else
			{
				// OH NOES ¬_¬
				die('A database error occurred whilst processing this page.
					Please contact the site administrator!');
			}
		}
	}

	/**
	 * Perform a delete query on the database.
	 *
	 * This will perform a delete query on $table (the table prefix is automatically prefixed) and returns the number of affected rows.
	 * <code>
	 * // DB is an alias for Database
	 * DB::delete('content', array('id = ?', 2));
	 *
	 * // limit it to 1, even though there could be more
	 * DB::delete('content', array('id = ? OR id = ?', 2, 3), 1);
	 * </code>
	 *
	 * @param string $table The table to access. The database prefix is automatically added.
	 * @param array $where An array where the first item is one or more comparision statements. Question marks (?) can optionally be used for binding. Then the second item and beyond are the values for the binds. These binds are automatically escaped for security. Example: array('`col1' > ? AND `col2` < ?', '12', '24');
	 * @param array $limit (optional) The number of rows to limit the query to.
	 * @return int The number of rows affected.
	 */
	public static function delete($table, $where, $limit = null) {
		if (!HR_DB_ENABLE)
			self::databaseIsntEnabledYouNoob();
		$start = microtime(true);
		$sql = "DELETE FROM `%s%s` %s%s";

		if (is_array($where))
		{
			if (count($where) > 1)
			{
				$prepare = array_slice($where, 1);
			}
			$where = 'WHERE ' . $where[0];
		}
		elseif (!is_null($where))
		{
			$where = 'WHERE ' . $where;
		}

		if (!is_null($limit))
		{
			$limit = " LIMIT " . $limit;
		}
		$sql = trim(sprintf($sql, HR_DB_PREFIX, $table, $where, $limit));
		$time = microtime(true) - $start;
		self::$totalTime += $time;

		Log::add('DB Query: (time: ' . $time . ') ' . $sql);

		$smt = self::getHandle()->prepare($sql);

		if (!$smt)
		{
			print_r(self::getHandle()->errorInfo());
			return false;
		}
		if ($smt->execute($prepare))
		{
			return $smt->rowCount();
		}
		else
		{
			if (HR_DB_DEBUG)
			{
				echo '<h1>DB Error:</h1>';
				print_r(self::getHandle()->errorInfo());
				echo "<h2>SQL Statement</h2><pre>$sql</pre>";
				die();
			}
			else
			{
				// OH NOES ¬_¬
				die('A database error occurred whilst processing this page.
					Please contact the site administrator!');
			}
		}
	}

	/**
	 * Perform a update query on the database.
	 *
	 * This will perform a update query on $table (the table prefix is automatically prefixed) and returns the number of affected rows.
	 * <code>
	 * // DB is an alias for Database
	 * DB::update('settings', array('package', 'name', 'data'), array('the package', 'the name', 'the data'), array('id = ?', 4));
	 *
	 * // same as above
	 * DB::update('settings', array('package' => 'the package', 'name' => 'the name', 'data' => 'the data'), null, array('id = ?', 4));
	 * </code>
	 *
	 * @param string $table The table to access. The database prefix is automatically added.
	 * @param array $cols Either the list of columns, or an associative array in the form `column => value`.
	 * @param array $values The respective values for $cols. The data is automatically sanitized. Optional if $cols is associative.
	 * @param array $where An array where the first item is one or more comparision statements. Question marks (?) can optionally be used for binding. Then the second item and beyond are the values for the binds. These binds are automatically escaped for security. Example: array('`col1' > ? AND `col2` < ?', '12', '24');
	 * @return int The number of rows affected.
	 */
	public static function update($table, $cols, $values = null, $where) {
		if (!HR_DB_ENABLE)
			self::databaseIsntEnabledYouNoob();
		$start = microtime(true);
		$prepare = array();

		$sql = "UPDATE `%s%s` SET%s %s";

		if (!is_array($values))
		{
			foreach ($cols as $key => $value)
			{
				$set .= " `$key` = ?,";
				$binds[] = $value;
			}
		}
		else
		{
			foreach ($cols as $key => $value)
			{
				$set .= " `$value` = ?,";
			}
			$binds = $values;
		}
		$set = ' ' . trim($set, ', ');

		// using where statement
		// should be like array('`col1' > ? AND `col2` < ?', '12', '24');
		if (is_array($where))
		{
			if (count($where) > 1)
			{
				$prepare = array_slice($where, 1);
			}
			$where = 'WHERE ' . $where[0];
		}
		elseif (!is_null($where))
		{
			$where = 'WHERE ' . $where;
		}

		if ($limit > 0 && is_numeric($limit))
		{
			$limit = "LIMIT " . $limit;
		}
		else
		{
			$limit = '';
		}

		$sql = trim(sprintf($sql, HR_DB_PREFIX, $table, $set, $where));
		$time = microtime(true) - $start;
		self::$totalTime += $time;

		Log::add('DB Query: (time: ' . $time . ') ' . $sql);
		$smt = self::getHandle()->prepare($sql);

		if (!$smt)
		{
			print_r(self::getHandle()->errorInfo());
			debug_print_backtrace();
			return false;
		}


		if ($smt->execute(array_merge($binds, $prepare)))
		{
			return $smt->rowCount();
		}
		else
		{
			if (HR_DB_DEBUG)
			{
				echo '<h1>DB Error:</h1>';
				print_r(self::getHandle()->errorInfo());
				echo "<h2>SQL Statement</h2><pre>$sql</pre>";
				die();
			}
			else
			{
				// OH NOES ¬_¬
				die('A database error occurred whilst processing this page.
					Please contact the site administrator!');
			}
		}
	}

}

class_alias('Database', 'DB');

try
{
	if (HR_DB_ENABLE)
	{
		// create the pdo object
		DB::setHandle(new PDO(HR_DSN, HR_DBUSR, HR_DBPASS));
	}
} catch (PDOException $e)
{
	die('DB ERROR: ' . $e);
}
