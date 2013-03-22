<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Server/ServerAPISearchServices.php';

class ServerAPISearchServicesTests extends PHPUnit_Framework_TestCase
{
	private $service;
	private $searchServicesMock;
		
	public function setUp()
	{
		$this->searchServicesMock = $this->getMock("ISearchAPIService", array("keywordSearch"));

		$this->service = new ServerAPISearchServices($this->searchServicesMock);
		parent::setUp();		
	}
	
	/**
	 * method searchKeyword
	 * when called
	 * should executesCorrectly
	 */
	public function test_searchKeyword_called_executesCorrectly()
	{
		$expected = "aDummyResultSearch";
		$lookFor = "aDummySearchTerm";
		$page = 1;
		$formatCategory = "aDummyFormatCategory";
	
		$this->searchServicesMock->expects($this->once())
								->method("keywordSearch")
								->with($this->equalTo($lookFor), $this->equalTo($page), $this->equalTo($formatCategory))
								->will($this->returnValue($expected));
		
		$actual = $this->service->searchKeyword($lookFor, $page, $formatCategory);
		$this->assertEquals($expected, $actual);
	}
}
?>