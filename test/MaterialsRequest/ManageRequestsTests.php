<?php
require_once 'services/MaterialsRequest/ManageRequests.php';
require_once 'sys/User.php';
require_once 'CatalogConnection.php';
require_once 'Drivers/marmot_inc/Library.php';

class ManageRequestsTests extends PHPUnit_Framework_TestCase
{	
	private $service;
	
	public function setUp()
	{
		global $configArray;
		global $interface;
		global $user;
		
		// setup DB_DataObject
		defined('DB_DATAOBJECT_NO_OVERLOAD') or define('DB_DATAOBJECT_NO_OVERLOAD', 0);
		$options =& PEAR::getStaticProperty('DB_DataObject', 'options');
		$options = $configArray['Database'];
				
		// load the user with id=1 (Mark Noble)
		$user = new User();
		$user->id = 1;
		$user->find(true);
		
		// setup $_REQUEST
		$_REQUEST['printSelected'] = 'print';
		$_REQUEST['select'] = array('8927'=>'on','9081'=>'on','9176'=>'on','9349'=>'on','7908'=>'on','9003'=>'on');
		
		$this->service = new ManageRequests();

		parent::setUp();
	}
	
	
	/**
	* method getTitleAuthorImage
	* when called
	* should executesCorrectly
	*/
	public function test_printSelectedRequests_called_executesCorrectly()
	{
		$expectedFile = dirname(__FILE__).'/../testFiles/MaterialsRequests.pdf';
		$expected = md5_file($expectedFile);
		$actualFile = $this->service->printSelectedRequests('MaterialsRequests.pdf', 'S');
		$actual = md5($actualFile);
		$this->assertEquals($expected, $actual);
	}
	
	public function tearDown()
	{
	}
}

?>