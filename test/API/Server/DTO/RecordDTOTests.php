<?php
require_once dirname(__FILE__).'/../../../../vufind/classes/API/Server/DTO/RecordDTO.php';
require_once dirname(__FILE__).'/../../../../vufind/classes/interfaces/IGenericRecord.php';

class RecordDTOTests extends PHPUnit_Framework_TestCase
{
  
 	 protected $service;
	 protected $resourceMock;
	 
	 public function setUp()
	 {
	 	$this->resourceMock = $this->getMock("IGenericRecord");
   		$this->service = new RecordDTO();
	 	parent::setUp();
	 }
	 
	 /**
	  * method getDTO
	  * when called
	  * should executesCorrectly
	  */
	 public function test_getDTO_called_executesCorrectly()
	 {
	 	$title = "aDummyTitle";    		     $author = "aDummyAuthor";
	 	$secondAuthor = "aDummySecAuthor";   $series = "aDummySeries";
	 	$isbn = "aDummyISBN";  	 	         $issn = "aDummyISSN";
	 	$ean = "aDummyEAN";    		         $publisher = "aDummyPublisher";
	 	$year = "aDummyYear"; 		         $edition = "aDummyEdition";
	 	$url = "aDummyURL";		             $uniqID = "aDummyUniqueId";
	 	$publicationPlace = "aDummyPublicationDate";
	 
	 	$expected['title'] = $title;
	 	$expected['author'] = $author;
	 	$expected['secondAuthor'] = $secondAuthor;
	 	$expected['series'] = $series;
	 	$expected['isbn'] = $isbn;
	 	$expected['issn'] = $issn;
	 	$expected['ean'] = $ean;
	 	$expected['publicationPlace'] = $publicationPlace;
	 	$expected['publisher'] = $publisher;
	 	$expected['year'] = $year;
	 	$expected['edition'] = $edition;
	 	$expected['url'] = $url;
	 	$expected['uniqueID'] = $uniqID;
	 
	 	$this->prepareValueResult("getTitle", $title);
	 	$this->prepareValueResult("getAuthor", $author);
	 	$this->prepareValueResult("getSecondaryAuthor", $secondAuthor);
	 	$this->prepareValueResult("getSeries", $series);
	 	$this->prepareValueResult("getISBN", $isbn);
	 	$this->prepareValueResult("getISSN", $issn);
	 	$this->prepareValueResult("getEAN", $ean);
	 	$this->prepareValueResult("getPublicationPlace", $publicationPlace);
	 	$this->prepareValueResult("getPublisher", $publisher);
	 	$this->prepareValueResult("getYear", $year);
	 	$this->prepareValueResult("getEdition", $edition);
	 	$this->prepareValueResult("getPermanentPath", $url);
	 	$this->prepareValueResult("getUniqueSystemID", $uniqID);
	 
	 	$actual = $this->service->getDTO($this->resourceMock);
	 	$this->assertEquals($expected, $actual);
	 }
	 
	 //privates
	 private function prepareValueResult($method, $returnValue)
	 {
	 	$this->resourceMock->expects($this->once())
	 	->method($method)
	 	->will($this->returnValue($returnValue));
	 }

}
?>