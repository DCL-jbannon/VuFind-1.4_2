<?php

require_once dirname(__FILE__).'/../../vufind/web/sys/serverAPI/APISessions.php';

class APISessionsTests extends PHPUnit_Framework_TestCase
{
	private $service;

	public function setUp()
	{
		$this->service = new APISessions();
		parent::setUp();
	}
	
	/**
	* method getCreatedOnTS 
	* when called
	* should returnCorrectly
	*/
	public function test_getCreatedOnTS_called_returnCorrectly()
	{
		$expected = "1361910461";
		$this->service->setCreatedOn("2013-02-26 13:27:41");
		$actual = $this->service->getCreatedOnTS();
		$this->assertEquals($expected, $actual);
	}
	
		

}
?>