<?php
require_once 'PEAR.php';
require_once 'DB/DataObject.php';

require_once dirname(__FILE__).'/../vufind/web/sys/ConfigArray.php';

global $servername;
global $configArray;

define ('TEST_DBUSER', 'root');

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	define ('TEST_SERVERNAME', 'dcl.localhost');
	define ('TEST_DBUSERPASS', '');
	
} else {
	define ('TEST_SERVERNAME', 'dclinux.localhost');
	define ('TEST_DBUSERPASS', 'dev');
}
 
$servername = TEST_SERVERNAME;
$_SERVER['SERVER_NAME'] = TEST_SERVERNAME;

$configArray = readConfig();

$_SERVER['REQUEST_URI'] = '/';
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__).'/../vufind/web');
require_once 'sys/Timer.php';
global $timer;
$timer = new Timer(microtime(true));
setlocale(LC_MONETARY, array($configArray['Site']['locale'] . ".UTF-8",
		$configArray['Site']['locale']));
date_default_timezone_set($configArray['Site']['timezone']);
require_once 'Drivers/marmot_inc/Library.php';
require_once 'Drivers/marmot_inc/Location.php';
// unset the mobile_theme configuration to prevent UInterface
// from setting ui cookie, which will cause phpunit to complain
unset($configArray['Site']['mobile_theme']);
// Start Interface
require_once 'sys/Interface.php';
global $interface;
$interface = new UInterface();
?>