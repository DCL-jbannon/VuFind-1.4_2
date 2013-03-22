<?php

require_once dirname(__FILE__).'/MaterialsRequest/ManageRequestsTests.php';

class SuiteMaterialsRequestTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('MaterialsRequest-Tests');
		$suite->addTestSuite('ManageRequestsTests');
		return $suite;
	}
}

?>