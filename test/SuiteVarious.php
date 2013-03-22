<?php
require_once dirname(__FILE__).'/classes/mail/PHPMailerWrapperTests.php';
require_once dirname(__FILE__).'/classes/mail/MailServicesTests.php';
require_once dirname(__FILE__).'/classes/notifications/ReturnEcontentNotificationTests.php';
require_once dirname(__FILE__).'/Integration/IntegrationTests.php';
require_once dirname(__FILE__).'/classes/memcache/MemcacheWrapperTests.php';
require_once dirname(__FILE__).'/classes/memcache/MemcacheServicesTests.php';
require_once dirname(__FILE__).'/classes/memcache/ClassCacheTests.php';

class SuiteVariousTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Various-Tests');
		$suite->addTestSuite('PHPMailerWrapperTests');
		$suite->addTestSuite('ReturnEcontentNotificationTests');
		$suite->addTestSuite('MailServicesTests');
		$suite->addTestSuite('IntegrationTests');
		$suite->addTestSuite('MemcacheWrapperTests');
		$suite->addTestSuite('MemcacheServicesTests');
		$suite->addTestSuite('ClassCacheTests');
		return $suite;
	}
}

?>