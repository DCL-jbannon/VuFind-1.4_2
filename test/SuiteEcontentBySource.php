<?php
require_once dirname(__FILE__).'/econtentBySource/EcontentDetailsFactoryTests.php';
require_once dirname(__FILE__).'/econtentBySource/ThreemRecordDetailsTests.php';
require_once dirname(__FILE__).'/econtentBySource/EcontentRecordShowButtonsTests.php';
require_once dirname(__FILE__).'/econtentBySource/EcontentRecordStatusTextTests.php';

class SuiteEcontentBySourceTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Econtent By Source');
		$suite->addTestSuite('EcontentDetailsFactoryTests');
		$suite->addTestSuite('ThreemRecordDetailsTests');
		$suite->addTestSuite('EcontentRecordStatusTextTests');
		$suite->addTestSuite('EcontentRecordShowButtonsTests');
		return $suite;
	}
}

?>