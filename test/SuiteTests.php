<?php
require_once dirname(__FILE__).'/SuiteVarious.php';
require_once dirname(__FILE__).'/SuiteTestUtils.php';
require_once dirname(__FILE__).'/SuiteTestServices.php';
require_once dirname(__FILE__).'/SuiteTestAPIs.php';
require_once dirname(__FILE__).'/SuiteEcontentBySource.php';
require_once dirname(__FILE__).'/SuiteMonitor.php';
require_once dirname(__FILE__).'/SuiteDAOTests.php';
require_once dirname(__FILE__).'/SuiteEntities.php';
require_once dirname(__FILE__).'/SuiteMaterialsRequest.php';

class SuiteTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Suite-Tests');
		
		$suite->addTestSuite('SuiteDAOTests');
		$suite->addTestSuite('SuiteUtilsTests');
		$suite->addTestSuite('SuiteServicesTests');
		$suite->addTestSuite('SuiteVariousTests');
		$suite->addTestSuite('SuiteEcontentBySourceTests');
		$suite->addTestSuite('SuiteEntitiesTests');
		$suite->addTestSuite('SuiteMaterialsRequestTests');
		$suite->addTestSuite('SuiteMonitorTests');
		$suite->addTestSuite('SuiteAPIsTests');
		return $suite;
	}
}

?>