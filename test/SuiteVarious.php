<?php

require_once dirname(__FILE__).'/classes/EContentRecordTests.php';
require_once dirname(__FILE__).'/classes/UserTests.php';
class SuiteVariousTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Various-Tests');
		$suite->addTestSuite('EContentRecordTests');
		$suite->addTestSuite('UserTests');
		return $suite;
	}
}

?>