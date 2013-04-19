<?php
require_once dirname(__FILE__).'/API/OverDrive/OverDriveAPIWrapperTests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveAPITests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveServicesAPITests.php';
require_once dirname(__FILE__).'/API/OverDrive/CollectionOverDriveIteratorTests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveHttpResponseTests.php';

require_once dirname(__FILE__).'/API/OverDrive/OverDriveSSUtilsTests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveSSDOMXPathTests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveSSTests.php';
require_once dirname(__FILE__).'/API/OverDrive/OverDriveCacheSSTests.php';

require_once dirname(__FILE__).'/API/Google/GoogleBooksAPIWrapperTests.php';
require_once dirname(__FILE__).'/API/Freegal/FreegalAPIWrapperTests.php';
require_once dirname(__FILE__).'/API/Freegal/FreegalAPIServicesTests.php';
require_once dirname(__FILE__).'/API/3M/ThreeMAPIUtilsTests.php';
require_once dirname(__FILE__).'/API/3M/ThreeMAPIWrapperTests.php';
require_once dirname(__FILE__).'/API/3M/ThreeMAPITests.php';

/** SERVER API RELATED **/
require_once dirname(__FILE__).'/API/Server/ServerAPIUtilsTests.php';
require_once dirname(__FILE__).'/API/Server/ServerAPIServicesTests.php';
require_once dirname(__FILE__).'/API/Server/ServerAPITests.php';
require_once dirname(__FILE__).'/API/Server/ServerAPIRebusListServicesTests.php';
require_once dirname(__FILE__).'/API/Server/ServerAPIItemServicesTests.php';
require_once dirname(__FILE__).'/API/Server/ServerAPISearchServicesTests.php';
require_once dirname(__FILE__).'/API/Server/DTO/RecordDTOTests.php';

/** SirsiDynix API RELATED **/
require_once dirname(__FILE__).'/API/SirsiDynix/SisriDynixAPIWrapperTests.php';


/** Novelist API RELATED **/
require_once dirname(__FILE__).'/API/Novelist/NovelistWrapperTests.php';
require_once dirname(__FILE__).'/API/Novelist/NovelistServicesTests.php';

class SuiteAPIsTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('APIs-Tests');
		$suite->addTestSuite('CollectionOverDriveIteratorTests');
		$suite->addTestSuite('GoogleBooksAPIWrapperTests');
		$suite->addTestSuite('FreegalAPIWrapperTests');
		$suite->addTestSuite('FreegalAPIServicesTests');
		$suite->addTestSuite('ThreeMAPIUtilsTests');
		$suite->addTestSuite('ThreeMAPITests');
		$suite->addTestSuite('ThreeMAPIWrapperTests');
		
		$suite->addTestSuite('OverDriveSSUtilsTests');
		$suite->addTestSuite('OverDriveSSDOMXPathTests');
		$suite->addTestSuite('OverDriveSSTests');
		$suite->addTestSuite('OverDriveCacheSSTests');
		$suite->addTestSuite('OverDriveServicesAPITests');
		$suite->addTestSuite('OverDriveAPIWrapperTests');
		$suite->addTestSuite('OverDriveAPITests');
		$suite->addTestSuite('OverDriveHttpResponseTests');
		
		/** SERVER API RELATED **/
		$suite->addTestSuite('ServerAPIUtilsTests');
		$suite->addTestSuite('ServerAPIServicesTests');
		$suite->addTestSuite('ServerAPITests');
		$suite->addTestSuite('RecordDTOTests');
		$suite->addTestSuite('ServerAPIRebusListServicesTests');
		$suite->addTestSuite('ServerAPIItemServicesTests');
		$suite->addTestSuite('ServerAPISearchServicesTests');
		
		/** SirsiDynix API RELATED **/
		$suite->addTestSuite('SisriDynixAPIWrapperTests');
		
		/** Novelist API RELATED **/
		$suite->addTestSuite('NovelistWrapperTests');
		$suite->addTestSuite('NovelistServicesTests');
		
		return $suite;
	}
}
?>