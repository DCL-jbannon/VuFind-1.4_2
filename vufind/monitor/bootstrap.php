<?php
global $trans, $configArray;
require_once dirname(__FILE__).'/functions.php';
require_once dirname(__FILE__).'/../classes/monitor/CoversDirectoryMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/FreegalMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/GoogleMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/HIPMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/HIPDBMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/LibraryThingMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/MysqlMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/OverDriveMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/SIP2Monitor.php';
require_once dirname(__FILE__).'/../classes/monitor/SolrMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/SyndeticsMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/ThreeMMonitor.php';
require_once dirname(__FILE__).'/../classes/monitor/MemcacheMonitor.php';

if(empty($_GET))
{
	echo "<h1>You must specify which service you want to monitor calling with the parameter method:</h1></br>";
	getInformation();
	exit(0);
}

if(!isset($_GET['method']))
{
	echo "<h1>You must specify which service you want to monitor calling with the parameter method:</h1></br>";
	getInformation();
	exit(0);
}

$config = parse_ini_file(dirname(__FILE__).'/../../sites/dcl.localhost/conf/monitor.ini', true);

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
{
   $config['System']['operatingSystem'] == 'windows'
}
else
{
   $config['System']['operatingSystem'] == 'linux';

}
$configArray = $config;
?>
