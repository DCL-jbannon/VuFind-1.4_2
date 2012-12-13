<?php

require_once dirname(__FILE__).'/API/OverDrive/OverDriveAPIWrapperTests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveAPITests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveServicesAPITests.php';
require_once dirname(__FILE__).'/API/OverDrive/CollectionOverDriveIteratorTests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveHttpResponseTests.php';
require_once dirname(__FILE__).'/API/Google/GoogleBooksAPIWrapperTests.php';
require_once dirname(__FILE__).'/API/Freegal/FreegalAPIWrapperTests.php';
require_once dirname(__FILE__).'/API/Freegal/FreegalAPIServicesTests.php';

class SuiteAPIsTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('APIs-Tests');
		$suite->addTestSuite('OverDriveAPIWrapperTests');
		$suite->addTestSuite('OverDriveAPITests');
		$suite->addTestSuite('OverDriveServicesAPITests');
		$suite->addTestSuite('OverDriveHttpResponseTests');
		$suite->addTestSuite('CollectionOverDriveIteratorTests');
		$suite->addTestSuite('GoogleBooksAPIWrapperTests');
		$suite->addTestSuite('FreegalAPIWrapperTests');
		$suite->addTestSuite('FreegalAPIServicesTests');
		return $suite;
	}
}

?>