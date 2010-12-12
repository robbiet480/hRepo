<?php

/*
style info:

always tabs.
braces on the same line.
camelCase classes, variables and methods
under_scores in function names and file names
filesnames are all lowercase
prefix functions and constants with hr_ (for hRepo) for differntiantion from standard funcs
always use braces on if, else, for, while, foreach, etc.
never use the closing ?> unless needed

all urls end in /
*/

// benchmarks
define('HR_START', microtime(true));

define('HR_ROOT', realpath(dirname(__FILE__).'/../').'/');
define('HR_PAGES', HR_ROOT.'pages/');
define('HR_PUB', HR_ROOT.'pub/');
define('HR_LIB', HR_ROOT.'lib/');
define('HR_INC', HR_ROOT.'inc/');
define('HR_PUB_ROOT', '/'.ltrim(dirname($_SERVER['PHP_SELF']).'/', '/'));
define('HR_TEMPLATE', HR_ROOT.'template/');
define('HR_TMP', HR_ROOT.'tmp/');

define('HR_TEMPLATE_TO_USE', 'default');
define('HR_TEMPLATE_PUB_ROOT', HR_PUB_ROOT.'static/'.HR_TEMPLATE_TO_USE.'/');

define('HR_DB_ENABLE', false);
define('HR_DSN', 'mysql:host=localhost;dbname=testdb');
define('HR_DBUSR', 'user');
define('HR_DBPASS', 'password');
define('HR_DB_PREFIX', '');

error_reporting(E_ALL - E_NOTICE);

if (isset($_SERVER['PATH_INFO'])) {
	$_GET['page'] = $_SERVER['PATH_INFO']; // if the rewriting is on...
}

$_GET['page'] = rtrim($_GET['page'], '/');
$parts = explode('/',$_GET['page']);
if(count($parts) > 1) {
	$slug = $parts[1];
} else {
	$slug = 'index';
}
unset($parts);

// format: $nav['browse'] = array('url' => '/browse', 'slug' => 'browse', 'name' => 'Browse', 'loggedInOnly' => false, 'weight' => 1);
$nav = array();

require(HR_INC.'logging.php');
require(HR_INC.'std.php');

inc('db.php');
inc('content.php');
inc('sidebar.php');
inc('user.php');
inc('template.php');

foreach(glob(HR_PAGES.'*.php') as $page) {
	require($page);
}

template();
