<?php
//MySQL Information
$sqlhost = '';
$sqluser = '';
$sqlpass = '';
$sqldb = '';
	
//Connect to MySQL and select a database
$connection = mysql_connect($sqlhost, $sqluser, $sqlpass);
mysql_select_db($sqldb, $connection);
?>