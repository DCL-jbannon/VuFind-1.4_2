<?php

require_once dirname(__FILE__).'/../../../vufind/classes/covers/GoogleBooksCovers.php';
require_once dirname(__FILE__).'/../../mother/GoogleAPI/GoogleAPIResultsMother.php';

class GoogleBooksCoversTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $googleAPIMother;
	private $googleAPIWrapper;
	
	public function setUp()
	{
		$this->googleAPIWrapper = $this->getMock("IGoogleBooksAPIWrapper", array("getBookInfo"));
		$this->googleAPIMother = new GoogleAPIResultsMother();
		$this->service = new GoogleBooksCovers($this->googleAPIWrapper);
		parent::setUp();		
	}
	
	
	/**
	* method getImageUrl
	* when called
	* should returnCorrectURL
	* @dataProvider DP_getImageUrl
	*/
	public function test_getImageUrl_sizeSmall_returnCorrectURL($size, $expected)
	{
		$isbn = "aDummyValidISBN";
		$resultGetBookInfo = $this->googleAPIMother->getBookInfoIBSN($isbn);
		
		$this->googleAPIWrapper->expects($this->once())
							   ->method("getBookInfo")
							   ->with($this->equalTo($isbn))
							   ->will($this->returnValue($resultGetBookInfo));
		
		$actual = $this->service->getImageUrl($isbn, $size);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getImageUrl()
	{
		return array(
				  		array("small", "http://bks8.books.google.com/books?id=niMxoYVApysC&printsec=frontcover&img=1&zoom=5&edge=curl"),
						array("medium", "http://bks8.books.google.com/books?id=niMxoYVApysC&printsec=frontcover&img=1&zoom=1&edge=curl"),
						array("large", "http://bks8.books.google.com/books?id=niMxoYVApysC&printsec=frontcover&img=1&zoom=2&edge=curl"),	
						array("a Non Valid Size", "http://bks8.books.google.com/books?id=niMxoYVApysC&printsec=frontcover&img=1&zoom=5&edge=curl"),
					);
	}
	
	/**
	* method getImageUrl
	* when isbnNotFoundGoogleBooks
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_isbnNotFoundGoogleBooks_throw()
	{
		$isbn = "aDummyISBNNotFoundOnGoogleBooks";
		$resultGetBookInfo = $this->googleAPIMother->getBookInfoIBSNNotFound();
		
		$this->googleAPIWrapper->expects($this->once())
							   ->method("getBookInfo")
							   ->with($this->equalTo($isbn))
							   ->will($this->returnValue($resultGetBookInfo));
		$this->service->getImageUrl($isbn, "a Dummy Value");
	}
	
	/**
	* method getImageUrl
	* when BookFoundWithNoThumbnailURL
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_BookFoundWithNoThumbnailURL_throw()
	{
		$isbn = "aDummyISBNNotFoundOnGoogleBooks";
		$resultGetBookInfo = $this->googleAPIMother->getBookInfoIBSNWithNoThumbnailURl($isbn);
		
		$this->googleAPIWrapper->expects($this->once())
								->method("getBookInfo")
								->with($this->equalTo($isbn))
								->will($this->returnValue($resultGetBookInfo));
		$this->service->getImageUrl($isbn, "a Dummy Value");
	}

}

?>