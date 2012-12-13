<?php
require_once dirname(__FILE__).'/../../../vufind/classes/covers/SyndeticsCovers.php';

class SyndeticsCoversTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new SyndeticsCovers();
		parent::setUp();
	}
	
	/**
	* method getImageUrl
	* when called
	* should executeCorrectly
	* @dataProvider DP_getImageUrl
	*/
	public function test_getImageUrl_called_executeCorrectly($size, $category, $expected)
	{
		$isbn = "aDummyISBN";
		$upc = "aDummyUPC";
		$clientId = "aDummyClientId";
		$actual = $this->service->getImageUrl($isbn, $upc, $size, $clientId, $category);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getImageUrl()
	{
		return array(
				  		array("small","aDummyCategory","http://syndetics.com/index.aspx?type=xw12&isbn=aDummyISBN/SC.GIF&client=aDummyClientId&upc=aDummyUPC"),
						array("medium","aDummyCategory","http://syndetics.com/index.aspx?type=xw12&isbn=aDummyISBN/MC.GIF&client=aDummyClientId&upc=aDummyUPC"),
						array("large","aDummyCategory","http://syndetics.com/index.aspx?type=xw12&isbn=aDummyISBN/LC.JPG&client=aDummyClientId&upc=aDummyUPC"),
						array("aNonValidSize","aDummyCategory","http://syndetics.com/index.aspx?type=xw12&isbn=aDummyISBN/LC.JPG&client=aDummyClientId&upc=aDummyUPC")
					);
	}
	
	/**
	* method getImageUrl
	* when categoryBooks
	* should returnWithNoUPC
	*/
	public function test_getImageUrl_categoryBook_returnWithNoUPC()
	{
		$expected = "http://syndetics.com/index.aspx?type=xw12&isbn=aDummyISBN/LC.JPG&client=aDummyClientId";
		$category = "Books";
		$isbn = "aDummyISBN";
		$upc = "aDummyUPC";
		$clientId = "aDummyClientId";
		$size = "aDummySize";
		$actual = $this->service->getImageUrl($isbn, $upc, $size, $clientId, $category);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getImageUrl
	* when isbnEmpty
	* should executesCorrectly
	*/
	public function test_getImageUrl_isbnEmpty_executesCorrectly()
	{
		$expected = "http://syndetics.com/index.aspx?type=xw12&isbn=/LC.JPG&client=aDummyClientId";
		$isbn = "";
		$upc = "aDummyUPC";
		$clientId = "aDummyClientId";
		$size = "aDummySize";
		$this->service->getImageUrl($isbn, $upc, $size, $clientId);
	}
		
	

}


?>