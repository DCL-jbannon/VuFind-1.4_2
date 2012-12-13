<?php
require_once dirname(__FILE__).'/../../../vufind/classes/covers/OpenLibraryCovers.php';

class OpenLibraryCoversTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new OpenLibraryCovers();
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
		$actual = $this->service->getImageUrl($isbn, $size);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getImageUrl()
	{
		return array(
				  		array("large","http://covers.openlibrary.org/b/isbn/9780451209337-L.jpg?default=false"),
						array("medium","http://covers.openlibrary.org/b/isbn/9780451209337-M.jpg?default=false"),
						array("small;","http://covers.openlibrary.org/b/isbn/9780451209337-S.jpg?default=false"),
						array("aNonValidSize","http://covers.openlibrary.org/b/isbn/9780451209337-S.jpg?default=false")
					);
	}
	
	/**
	* method getImageUrl
	* when isbnIsEmpty
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_isbnIsEmpty_throw()
	{
		$isbn = "";
		$this->service->getImageUrl($isbn, "aDummySizes");
	}
	
}

?>