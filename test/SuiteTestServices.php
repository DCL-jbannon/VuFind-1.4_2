<?php

require_once dirname(__FILE__).'/services/IDCLReaderServicesTests.php';
require_once dirname(__FILE__).'/services/EContentRecordServicesTests.php';
require_once dirname(__FILE__).'/services/FreeEContentRecordServicesTests.php';
require_once dirname(__FILE__).'/services/OverDriveServicesTests.php';
require_once dirname(__FILE__).'/services/EContentRecordServicesTests.php';
require_once dirname(__FILE__).'/services/FreegalServicesTests.php';
require_once dirname(__FILE__).'/services/NotificationServicesTests.php';
require_once dirname(__FILE__).'/services/RebusListServicesTests.php';
require_once dirname(__FILE__).'/services/RecordServicesTests.php';

class SuiteServicesTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Services-Tests');
		$suite->addTestSuite('IDCLReaderServicesTests');
		$suite->addTestSuite('EContentRecordServicesTests');
		$suite->addTestSuite('FreeEcontentRecordServicesTests');
		$suite->addTestSuite('OverDriveServicesTests');
		$suite->addTestSuite('EContentRecordServicesTests');
		$suite->addTestSuite('FreegalServicesTests');
		$suite->addTestSuite('NotificationServicesTests');
		$suite->addTestSuite('RebusListServicesTests');
		$suite->addTestSuite('RecordServicesTests');
		return $suite;
	}
}

?>