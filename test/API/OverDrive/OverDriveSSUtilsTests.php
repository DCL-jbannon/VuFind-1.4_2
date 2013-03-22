<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveSSUtils.php';

class OverDriveSSUtilsTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
		
	public function setUp()
	{
		$this->service = new OverDriveSSUtils();
		parent::setUp();		
	}
	
	/**
	* method getSessionString 
	* when called
	* should returnCorrectly
	*/
	public function test_getSessionString_called_returnCorrectly()
	{
		$url = "http://www.emedia2go.org/32F320C5-6587-43FD-8ECF-0175AD0B3950/10/50/en/Default.htm";
		$expected = "32F320C5-6587-43FD-8ECF-0175AD0B3950";
		$actual = $this->service->getSessionString($url);
		$this->assertEquals($expected, $actual);
	}

}
?>