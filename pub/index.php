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
define('HR_PUB_ROOT', 'http://'.$_SERVER['HTTP_HOST'].'/'.ltrim(dirname($_SERVER['PHP_SELF']).'/', '/')); // I guess it's bad practice to put a variable into a constant BUT HEY
define('HR_TEMPLATE', HR_ROOT.'template/');
define('HR_TMP', HR_ROOT.'tmp/');

define('HR_TEMPLATE_TO_USE', 'default');
define('HR_TEMPLATE_PUB_ROOT', HR_PUB_ROOT.'static/'.HR_TEMPLATE_TO_USE.'/');

define('HR_RECAPTCHA_PUBKEY', '6Lcy3b8SAAAAADfMXY86VFc8IT7AzeoQPC19G9aL');
define('HR_RECAPTCHA_PRIVKEY', '6Lcy3b8SAAAAAGHUxvta8txiu7uGniWAffWgTxPj');

define('HR_DB_ENABLE', true);
define('HR_DSN', 'mysql:host=localhost;port=3306;dbname=bukkit_fill2');
define('HR_DBUSR', 'bukkit_fill');
define('HR_DBPASS', 'eKd?X3}U_vzB'); // this can be shared, I guess, since MySQLd only binds to 127.0.0.1
define('HR_DB_PREFIX', '');

error_reporting(E_ALL - E_NOTICE);
ini_set('display_errors', 'On'); // this is helpful for DEBUGGING but remove it after the fact
define('HR_DB_DEBUG', true); // this makes magic happen

if (isset($_SERVER['PATH_INFO'])) {
	$_GET['page'] = $_SERVER['PATH_INFO']; // if the rewriting is on...
}

// URI fixing, to avoid google :( ing
$correctURI = ltrim($_GET['page'], '/');
if (strlen($correctURI) != 0 && substr($correctURI, -1, 1) != '/') {
	$correctURI = $correctURI . '/';
}
while (strpos($correctURI, '//') !== FALSE) {
	$correctURI = str_replace('//', '/', $correctURI);
}
if ('/' . $correctURI != $_GET['page'] && count($_POST) == 0 && strlen($_GET['page']) != 0) {
	header('Location: '. HR_PUB_ROOT . $correctURI);
	exit();
}
// end URI fix

$_GET['page'] = rtrim($_GET['page'], '/');
$parts = explode('/',$_GET['page']);
if(count($parts) > 1) {
	$slug = $parts[1];
} else {
	$slug = 'index';
}
if (count($parts) > 2) {
	$params = array_slice($parts, 2);
} else {
	$params = array();
}
unset($parts);

// format: $nav['browse'] = array('url' => '/browse', 'slug' => 'browse', 'name' => 'Browse', 'loggedInOnly' => false, 'weight' => 1);
$nav = array();

require(HR_INC.'logging.php');
require(HR_INC.'std.php');

// Load up the XenForo system
	Log::add('Begin initialising XenForo...');
	$startTime = microtime(true);
	//$fileDir = realpath('./../forums/');
	$fileDir = '/home2/bukkit/public_html/forums/';

	require($fileDir . '/library/XenForo/Autoloader.php');
	XenForo_Autoloader::getInstance()->setupAutoloader($fileDir . '/library');

	XenForo_Application::initialize($fileDir . '/library', $fileDir);
	XenForo_Application::set('page_start_time', $startTime);

	// Not required if you are not using any of the preloaded data
	$dependencies = new XenForo_Dependencies_Public();
	$dependencies->preLoadData();

	XenForo_Session::startPublicSession();
	Log:add('XF initialisation complete!');
// End XenForo

inc('db.php');
inc('content.php');
inc('sidebar.php');
inc('user.php');
inc('template.php');
inc('message.php');
inc('plugin.php');

// Mandatory include-everywhere libraries
inclib('phpmailer/class.phpmailer.php'); // because then we can set defaults here
$mailer = new PHPMailer();
$mailer->SetFrom('donotreply@hrepo.com', 'hRepo System');
$mailer->IsSendmail();

// Now check the user!
User::bootstrap();

foreach(glob(HR_PAGES.'*.php') as $page) {
	require($page);
}

template();
