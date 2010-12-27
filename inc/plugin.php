<?php

/**
 * This is the class that generically handles plugins!
 *
 * @author Luke
 */
class Plugin {

	private static $mappings = array(
		'pid' => 'id',
		'pauthor_id' => 'author_id',
		'pname' => 'name',
		'pdesc' => 'desc',
		'preqs' => 'reqs',
		'pmysql' => 'requires_mysql',
		'pdownloads' => 'downloads',
		'padded_date' => 'added_date',
		'prating' => 'rating',
		'pstatus' => 'status'
	);
	private $inited = 0; // shouldn't be modifiable. 0 = not inited, create new, 1 = from DB
	private $id = 0; // shouldn't be modifiable
	public $author_id = 0;
	public $name = '';
	public $desc = '';
	public $reqs = '';
	public $requires_mysql = ''; // 0 = no/na, 1 = can use, 2 = MUST use
	public $downloads = 0; // download counter
	public $added_date = ''; // date added to db
	public $rating = -1; // rating 1-5
	public $status = 0; // 0 = non-visible, 1 = visible, 2 = deprecated

	function Plugin($pluginid = -1) {
		if ($pluginid != -1)
			$this->loadData($pluginid);
	}

	function getID() {
		return $this->id;
	}

	function loadData($pluginid) {
		if (is_numeric($pluginid))
		{
			$dbh = Database::select('plugins', '*', array('pid = ?', $pluginid));
		}
		else
		{ // is a plugin name, shortcut function!
			$dbh = Database::select('plugins', '*', array('pname = ?', $pluginid));
		}
		if ($dbh->rowCount() != 1)
			throw new NoSuchPluginException();
		$dbr = $dbh->fetch(PDO::FETCH_ASSOC);
		foreach (self::$mappings as $fromdb => $toclassvar)
		{
			$this->$toclassvar = $dbr[$fromdb];
		}
		$this->inited = 2;
	}

	function saveData() {
		$dbarray = array();
		foreach (self::$mappings as $todb => $fromclassvar)
		{
			$dbarray[$todb] = $this->$fromclassvar;
		}
		if ($this->inited == 1)
		{
			$a = Database::update('plugins', $dbarray, null, array('pid = ?', $this->id));
		}
		else
		{
			unset($dbarray['pid']); // auto increment
			$dbarray['padded_date'] = date('Y-m-d H:i:s');
			$a = Database::insert('plugins', $dbarray);
			if ($a == 1)
				$this->id = Database::getHandle()->lastInsertId();
		}
		return ($a == 1) ? true : false;
	}
}
