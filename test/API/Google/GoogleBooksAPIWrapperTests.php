<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Google/GoogleBooksAPIWrapper.php';

class GoogleBooksAPIWrapperTests extends PHPUnit_Framework_TestCase
{
	private $service;
	
	public function setUp()
	{
		$this->service = new GoogleBooksAPIWrapper();
		parent::setUp();		
	}
	
	
	/**
	* method getBookInfoByISBN
	* when called
	* should executesCorrectly
	*/
	public function test_getBookInfoByISBN_called_executesCorrectly()
	{
		$expected = "";
		$isbn = "9780345529411";
		$actual = $this->service->getBookInfo($isbn);
		$this->assertEquals("stdClass",get_class($actual));
		$this->assertEquals("stdClass",get_class($actual->$isbn));
	}
	
}

?>