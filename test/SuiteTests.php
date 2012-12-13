<?php
require_once dirname(__FILE__).'/SuiteVarious.php';
require_once dirname(__FILE__).'/SuiteTestUtils.php';
require_once dirname(__FILE__).'/SuiteTestServices.php';
require_once dirname(__FILE__).'/SuiteTestAPIs.php';

class SuiteTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Suite-Tests');
		$suite->addTestSuite('SuiteUtilsTests');
		$suite->addTestSuite('SuiteServicesTests');
		$suite->addTestSuite('SuiteAPIsTests');
		$suite->addTestSuite('SuiteVariousTests');
		return $suite;
	}
}

?>