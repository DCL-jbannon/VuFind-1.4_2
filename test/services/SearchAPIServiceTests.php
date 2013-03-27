<?php
require_once dirname(__FILE__).'/../../vufind/classes/services/SearchAPIService.php';

class SearchAPIServiceTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $searchSolrObjetMock;
	private $resourceDAOMock;
	private $econtentRecordDAOMock;
	private $recordDTO;
	private $igenericRecordMock;
		
	public function setUp()
	{
		$this->igenericRecordMock = $this->getMock("IGenericRecord");
		$this->searchSolrObjetMock = $this->getMock("ISearchObject_Solr", array("init", "processSearch", "getResultRecordSet"));
		$this->resourceDAOMock = $this->getMock("IResourceDAO", array("getByRecordId"));
		$this->econtentRecordDAOMock = $this->getMock("IEcontentRecordDAO", array("getById"));
		$this->recordDTO = $this->getMock("IRecordDTO", array("getDTO"));
		
		$this->service = new SearchAPIService($this->searchSolrObjetMock, 
											  $this->recordDTO, 
											  $this->resourceDAOMock, 
											  $this->econtentRecordDAOMock);
		parent::setUp();		
	}

	/**
	* method keywordSearch 
	* when called
	* should executesCorrectly
	*/
	public function test_keywordSearch_called_executesCorrectly()
	{
		$lookfor = "aDummySearchTerm";
		$pageNumber = 2;
		$formatCategory = "aDummyFormatCategory";
		
		$recordSet[] = array("id"=>"458789");
		$recordSet[] = array("id"=>"econtentRecord181025");
		
		$resourceEntity = $this->igenericRecordMock;
		$resourceDTO = "aDummyResourceDTO";
		$econtentRecordEntity = $this->igenericRecordMock;
		$econtentRecordDTO = "aDummyEcontentRecordDTO";
		
		$expectedREQUEST['format_category'] = $formatCategory;
		$expectedREQUEST['page'] = $pageNumber;
		$expectedREQUEST['lookfor'] = $lookfor;
		$expectedREQUEST['basicType'] = 'Keyword';
		$expectedSESSION['shards'] = array("eContent", "Main Catalog");
		
		$expected[] = $resourceDTO;
		$expected[] = $econtentRecordDTO;
		
		$this->prepareSolrKeywordSearch($recordSet);
		
		$this->resourceDAOMock->expects($this->once())
							->method("getByRecordId")
							->with($this->equalTo("458789"))
							->will($this->returnValue($resourceEntity));
		
		$this->econtentRecordDAOMock->expects($this->once())
									->method("getById")
									->with($this->equalTo("181025"))
									->will($this->returnValue($econtentRecordEntity));
		
		$this->recordDTO->expects($this->at(0))
						->method("getDTO")
						->with($this->equalTo($resourceEntity))
						->will($this->returnValue($resourceDTO));
		
		$this->recordDTO->expects($this->at(1))
						->method("getDTO")
						->with($this->equalTo($econtentRecordEntity))
						->will($this->returnValue($econtentRecordDTO));
		
		
		$this->assertEmpty($_REQUEST);
		$this->assertTrue(!isset($_SESSION));
		
		$actual = $this->service->keywordSearch($lookfor, $pageNumber, $formatCategory);
		
		$this->assertEquals($expected, $actual);
		$this->assertEquals($expectedREQUEST, $_REQUEST);
		$this->assertEquals($expectedSESSION, $_SESSION);
		$this->assertEquals($expected, $actual);
	}	
	
	/**
	 * method keywordSearch
	 * when DAOResultEmpty
	 * should executesCorrectly
	 */
	public function test_keywordSearch_DAOResultEmpty_executesCorrectly()
	{
		$lookfor = "aDummySearchTerm";
		$pageNumber = 2;
		$formatCategory = "aDummyFormatCategory";
	
		$recordSet[] = array("id"=>"458789");
		$recordSet[] = array("id"=>"econtentRecord181025");
	
		$resourceEntity = $this->igenericRecordMock;
		$resourceDTO = "aDummyResourceDTO";
	
		$expectedREQUEST['format_category'] = $formatCategory;
		$expectedREQUEST['page'] = $pageNumber;
		$expectedREQUEST['lookfor'] = $lookfor;
		$expectedREQUEST['basicType'] = 'Keyword';
		$expectedSESSION['shards'] = array("eContent", "Main Catalog");
	
		$expected[] = $resourceDTO;
	
		$this->prepareSolrKeywordSearch($recordSet);
								
	
		$this->resourceDAOMock->expects($this->once())
								->method("getByRecordId")
								->with($this->equalTo("458789"))
								->will($this->returnValue($resourceEntity));
	
		$this->recordDTO->expects($this->at(0))
							->method("getDTO")
							->with($this->equalTo($resourceEntity))
							->will($this->returnValue($resourceDTO));
	
		$this->assertEmpty($_REQUEST);
		$this->assertTrue(!isset($_SESSION));
	
		$actual = $this->service->keywordSearch($lookfor, $pageNumber, $formatCategory);
	
		$this->assertEquals($expected, $actual);
		$this->assertEquals($expectedREQUEST, $_REQUEST);
		$this->assertEquals($expectedSESSION, $_SESSION);
		$this->assertEquals($expected, $actual);
	}
	
	//private 
	private function prepareSolrKeywordSearch($recordSet)
	{
		$this->searchSolrObjetMock->expects($this->once())
									->method("init")
									->with($this->equalTo("local"));
		
		$this->searchSolrObjetMock->expects($this->once())
									->method("processSearch")
									->with($this->equalTo(true), $this->equalTo(true));
		
		$this->searchSolrObjetMock->expects($this->once())
									->method("getResultRecordSet")
									->will($this->returnValue($recordSet));
	}
}
?>