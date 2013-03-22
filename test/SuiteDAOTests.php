<?php
require_once dirname(__FILE__).'/DAO/NotificationSentDAOTests.php';
require_once dirname(__FILE__).'/DAO/NotificationMailStatsDAOTests.php';
require_once dirname(__FILE__).'/DAO/MaterialsRequestDAOTests.php';
require_once dirname(__FILE__).'/DAO/APIClientsDAOTests.php';
require_once dirname(__FILE__).'/DAO/APISessionsDAOTests.php';
require_once dirname(__FILE__).'/DAO/UserDAOTests.php';
require_once dirname(__FILE__).'/DAO/ResourceDAOTests.php';
require_once dirname(__FILE__).'/DAO/EcontentRecordDAOTests.php';
require_once dirname(__FILE__).'/DAO/APIStatisticsDAOTests.php';

class SuiteDAOTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('DAO-Tests');
		$suite->addTestSuite('NotificationSentDAOTests');
		$suite->addTestSuite('NotificationMailStatsDAOTests');
		$suite->addTestSuite('MaterialsRequestDAOTests');
		$suite->addTestSuite('APIClientsDAOTests');
		$suite->addTestSuite('APISessionsDAOTests');
		$suite->addTestSuite('UserDAOTests');
		$suite->addTestSuite('ResourceDAOTests');
		$suite->addTestSuite('EcontentRecordDAOTests');
		$suite->addTestSuite('APIStatisticsDAOTests');
		return $suite;
	}
}
?>