<?php

require_once dirname(__FILE__).'/../../vufind/web/services/MyResearch/lib/User.php';

class UserTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
		
	public function setUp()
	{
		$this->service = new User();
		parent::setUp();		
	}
	
	/**
	* method getBarcode 
	* when called
	* should returnCorrectly
	*/
	public function test_getBarcode_called_returnCorrectly()
	{
		$expected = "aDummyCatUserName";
		$this->service->cat_username = "aDummyCatUserName";
		
		$actual = $this->service->getBarcode();
		
		$this->assertEquals($expected, $actual);
		
	}
}
?>