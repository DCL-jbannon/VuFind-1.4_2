<?php
require_once dirname(__FILE__).'/../../../vufind/classes/solr/SolrResponseHeader.php';

class SolrResponseHeaderTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new SolrResponseHeader();
		parent::setUp();		
	}
	
	/**
	* method set
	* when called
	* should executesCorrectly
	*/
	public function test_set_called_executesCorrectly()
	{
		$responseHeader = $this->getResponseHeader();
		
		$this->service->set($responseHeader);
		
		$this->assertEquals($responseHeader->status, $this->service->getStatus());
		$this->assertEquals($responseHeader->QTime, $this->service->getQTime());
		$this->assertEquals(6, count($this->service->getParams()));
	}
	
	/**
	* method getQueryString
	* when called
	* should returnCorrectValue
	*/
	public function test_getQueryString_called_returnCorrectValue()
	{
		$responseHeader = $this->getResponseHeader();
		$this->service->set($responseHeader);
		$actual = $this->service->getQueryString();
		$this->assertEquals($responseHeader->params->q, $actual);
	}
	
	
	/**
	* method getStart
	* when called
	* should returnCorrectValue
	*/
	public function test_getStart_called_returnCorrectValue()
	{
		$responseHeader = $this->getResponseHeader();
		$this->service->set($responseHeader);
		$actual = $this->service->getStart();
		$this->assertEquals($responseHeader->params->start, $actual);
	}
	
	/**
	 * method getRows
	 * when called
	 * should returnCorrectValue
	 */
	public function test_getRows_called_returnCorrectValue()
	{
		$responseHeader = $this->getResponseHeader();
		$this->service->set($responseHeader);
		$actual = $this->service->getRows();
		$this->assertEquals($responseHeader->params->rows, $actual);
	}
		
	//exercises
	private function getResponseHeader()
	{
		$responseHeader->status = 0;
		$responseHeader->QTime = "0.1";
		$responseHeader->params = new StdClass();
		$responseHeader->params->firstParam = "aDummyParamValue";
		$responseHeader->params->anotherParam = "anotherDummyParamValue";
		$responseHeader->params->paramsNameUnpredictable = "anotherSecondDummyParamValue";
		$responseHeader->params->q = "aDummyQueryString";
		$responseHeader->params->start = 2;
		$responseHeader->params->rows = 10;
		return $responseHeader;
	}
	
	
}

?>