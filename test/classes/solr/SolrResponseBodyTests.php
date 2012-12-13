<?php
require_once dirname(__FILE__).'/../../mother/solrDOCMother.php';
require_once dirname(__FILE__).'/../../../vufind/classes/solr/SolrResponseBody.php';

class SolrResponseBodyTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $solrDOCMother;
	private $SolrDOCMock;
	
	public function setUp()
	{
		$this->SolrDOCMock = $this->getMock("ISolrDOC", array("set"));
		$this->SolrDOCMock2 = $this->getMock("ISolrDOC", array("set"));
		$this->solrDOCMother = new SolrDOCMother();
		$this->service = new SolrResponseBody($this->SolrDOCMock);
		parent::setUp();		
	}
	
	
	/**
	* method setResponse
	* when addingDocsAsSolrDOC_Class
	* should executesCorrectly
	*/
	public function test_setResponse_addingDocsAsSolrDOC_Class_executesCorrectly()
	{
		$solrStdClassDOC = $this->solrDOCMother->getDOCStdClass("65989");
		$response = $this->getResponse(1, $solrStdClassDOC);
		
		$this->SolrDOCMock->expects($this->once())
						  ->method("set")
						  ->with($this->equalTo($solrStdClassDOC));
		
		$this->service->set($response);
		$this->assertEquals($this->SolrDOCMock, $this->service->getDocs()->current());
	}
	
	/**
	* method setResponse
	* when called
	* should executeCorrectly
	*/
	public function test_setResponse_called_executeCorrectly()
	{
		$response = $this->getResponse(2);
		
		$this->service->set($response);
		
		$this->assertEquals(4, $this->service->getNumFound());
		$this->assertEquals(0, $this->service->getStart());
		$this->assertEquals(2, $this->service->getNumDocs());
		$this->assertEquals("ArrayIterator", get_class($this->service->getDocs()));
	}
	
	
	//Exercise
	private function getResponse($numberOfDocs = 2, $solrStdClassDOC1 = NULL)
	{
		if(!$solrStdClassDOC1) $solrStdClassDOC1 = $this->solrDOCMother->getDOCStdClass("1111");
		$response = new stdClass();
		$response->numFound = 4;
		$response->start = 0;
		$response->docs[] = $solrStdClassDOC1;
		
		if($numberOfDocs == 2)
		{
			$solrStdClassDOC2 = $this->solrDOCMother->getDOCStdClass("2222");
			$response->docs[] = $solrStdClassDOC2;
		}
		
		return $response;
	}
	
}

?>