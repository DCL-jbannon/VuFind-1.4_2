<?php

require_once dirname(__FILE__).'/services/IDCLReaderServicesTests.php';
require_once dirname(__FILE__).'/services/EContentRecordServicesTests.php';
require_once dirname(__FILE__).'/services/FreeEContentRecordServicesTests.php';
require_once dirname(__FILE__).'/services/OverDriveServicesTests.php';
require_once dirname(__FILE__).'/services/EContentRecordServicesTests.php';
require_once dirname(__FILE__).'/services/FreegalServicesTests.php';

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
		return $suite;
	}
}

?>