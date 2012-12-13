<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/BookCoverURL.php';

class BookCoverURLTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new BookCoverURL();
		parent::setUp();		
	}
	
	

	/**
	* method getBookCoverUrl
	* when coverForEContent
	* should returnCorrectly
	*/
	public function test_coverForEContent_called_returnCorrectly()
	{
		$this->service->setBaseUrl("http://catalog.douglascountylibraries.org");
		$expected = "http://catalog.douglascountylibraries.org/bookcover.php?id=40830&econtent=true&isn=9781570618048&size=large&category=EMedia";
		$actual = $this->service->getBookCoverUrl('large', '9781570618048', '40830', true);
		$this->assertEquals($expected, $actual);
	}
	

}

?>