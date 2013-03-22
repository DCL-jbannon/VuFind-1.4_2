<?php
require_once dirname(__FILE__).'/entities/EContentRecordTests.php';
require_once dirname(__FILE__).'/entities/UserTests.php';
require_once dirname(__FILE__).'/entities/NotificationSentTests.php';
require_once dirname(__FILE__).'/entities/NotificationMailStatsTests.php';
require_once dirname(__FILE__).'/entities/APISessionsTests.php';
require_once dirname(__FILE__).'/entities/ResourceTests.php';

class SuiteEntitiesTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Entities-Tests');
		$suite->addTestSuite('EContentRecordTests');
		$suite->addTestSuite('UserTests');
		$suite->addTestSuite('NotificationSentTests');
		$suite->addTestSuite('NotificationMailStatsTests');
		$suite->addTestSuite('APISessionsTests');
		$suite->addTestSuite('ResourceTests');
		return $suite;
	}
}

?>