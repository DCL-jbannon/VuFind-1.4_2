<?php
require_once dirname(__FILE__).'/../../../vufind/classes/solr/SolrDOC.php';

class SolrDOCTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new SolrDOC();
		parent::setUp();		
	}
	
	
	/**
	* method set
	* when called
	* should returnCorrectly
	*/
	public function test_set_called_returnCorrectly()
	{
		$docTest = new stdClass();
		$docTest->id = "123123";
		$docTest->author = "aDummyAuthor";
		$docTest->description = "aDummyDescription";
		$docTest->origin = "OverDrive";
		$docTest->mysqlid = "aDummyMysqlId";
		$docTest->title = array("aDummyTitle");
		$docTest->issn = array("aDummyISSN");
		$docTest->isbn = array("aDummyISBN");
		$docTest->publishDate=array("aDummyPublishDate");
		
		$this->service->set($docTest);
		
		$this->assertEquals($this->service->getId(), "123123");
		$this->assertEquals($this->service->getAuthor(), "aDummyAuthor");
		$this->assertEquals($this->service->getDescription(), "aDummyDescription");
		$this->assertEquals($this->service->getOrigin(), "OverDrive");
		$this->assertEquals($this->service->getMysqlId(), "aDummyMysqlId");
		$this->assertEquals($this->service->getTitle(), "aDummyTitle");
		$this->assertEquals($this->service->getISSN(), "aDummyISSN");
		$this->assertEquals($this->service->getISBN(), "aDummyISBN");
		$this->assertEquals($this->service->getPublishDate(), "aDummyPublishDate");
		
	}
		
}


?>