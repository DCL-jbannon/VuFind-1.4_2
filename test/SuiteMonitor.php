<?php
require_once dirname(__FILE__).'/monitor/SolrMonitorTests.php';
require_once dirname(__FILE__).'/monitor/CoversDirectoryMonitorTests.php';
require_once dirname(__FILE__).'/monitor/MysqlMonitorTests.php';
require_once dirname(__FILE__).'/monitor/HIPMonitorTests.php';
require_once dirname(__FILE__).'/monitor/SIP2MonitorTests.php';
require_once dirname(__FILE__).'/monitor/OverDriveMonitorTests.php';
require_once dirname(__FILE__).'/monitor/ThreeMMonitorTests.php';
require_once dirname(__FILE__).'/monitor/FreegalMonitorTests.php';
require_once dirname(__FILE__).'/monitor/SyndeticsMonitorTests.php';
require_once dirname(__FILE__).'/monitor/GoogleMonitorTests.php';
require_once dirname(__FILE__).'/monitor/LibraryThingMonitorTests.php';
require_once dirname(__FILE__).'/monitor/MemcacheMonitorTests.php';
require_once dirname(__FILE__).'/monitor/HIPDBSybaseMonitorTests.php';

class SuiteMonitorTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Monitor-Tests');
		$suite->addTestSuite('CoversDirectoryMonitorTests');
		$suite->addTestSuite('MysqlMonitorTests');
		$suite->addTestSuite('HIPMonitorTests');
		$suite->addTestSuite('SIP2MonitorTests');
		$suite->addTestSuite('OverDriveMonitorTests');
		$suite->addTestSuite('ThreeMMonitorTests');
		$suite->addTestSuite('FreegalMonitorTests');
		$suite->addTestSuite('SyndeticsMonitorTests');
		$suite->addTestSuite('MemcacheMonitorTests');
		$suite->addTestSuite('HIPDBSybaseMonitorTests');
		$suite->addTestSuite('SolrMonitorTests');
		return $suite;
	}
}
?>