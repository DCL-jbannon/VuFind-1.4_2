<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Novelist/NovelistWrapper.php';

class NovelistWrapperTests extends PHPUnit_Framework_TestCase
{
	private $service;		
	public function setUp()
	{
		global $configArray;
		
		$configArray['NovelistAPI']['profile'] = "s9038887.main.novsel2";
		$configArray['NovelistAPI']['password'] = "dGJyMOPmtUqxrLFN";
		
		$this->service = new NovelistWrapper();
		parent::setUp();		
	}
	
	
	/**
	* method getInfoByISBN 
	* when called
	* should executesCorrectly
	*/
	public function test_getInfoByISBN_called_executesCorrectly()
	{
		$expected = $isbn = "9780441008537";
		$actual = $this->service->getInfoByISBN($isbn);
		
		$this->assertEquals($expected, $actual->TitleInfo->primary_isbn);
	}
}

?>