<?php
require_once dirname(__FILE__).'/../../../vufind/classes/covers/LibraryThingCovers.php';

class LibraryThingCoversTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new LibraryThingCovers();
		parent::setUp();		
	}
	

	/**
	* method getImageUrl
	* when called
	* should returnCorrectURL
	* @dataProvider DP_getImageUrl
	*/
	public function test_getImageUrl_called_returnCorrectURL($size, $expected)
	{
		$isbn = "9780451209337";
		$apiKey = "aValidAPIKey";
		$actual = $this->service->getImageUrl($apiKey, $isbn, $size);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getImageUrl()
	{
		return array(
				  		array("large","http://covers.librarything.com/devkey/aValidAPIKey/large/isbn/9780451209337"),
						array("medium","http://covers.librarything.com/devkey/aValidAPIKey/medium/isbn/9780451209337"),
						array("small","http://covers.librarything.com/devkey/aValidAPIKey/small/isbn/9780451209337"),
						array("aNonValidSize","http://covers.librarything.com/devkey/aValidAPIKey/large/isbn/9780451209337")
					);
	}
	
	/**
	* method getImageUrl
	* when ISBNEmpty
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_ISBNEmpty_throw()
	{
		$isbn = "";
		$apiKey = "aValidAPIKey";
		$this->service->getImageUrl($apiKey, $isbn, "aDummySize");
	}
}

?>