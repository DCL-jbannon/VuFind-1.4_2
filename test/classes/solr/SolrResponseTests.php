<?php
require_once dirname(__FILE__).'/../../../vufind/classes/solr/SolrResponse.php';

class SolrResponseTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $responseBodyMock;
	private $responseHeaderMock;
	
	public function setUp()
	{
		$this->responseHeaderMock = $this->getMock("ISolrResponseHeader", array("set","getQueryString","getStart","getRows"));
		$this->responseBodyMock = $this->getMock("ISolrResponseBody", array("set","getDocs","getNumFound","getNumDocs"));
		$this->service = new SolrResponse($this->responseHeaderMock, $this->responseBodyMock);
		parent::setUp();
	}
	
	/**
	* method set
	* when called
	* should executesCorrectly
	*/
	public function test___construct_called_executesCorrectly()
	{
		$response = $this->getResponse();
		
		$this->responseHeaderMock->expects($this->once())
								->method("set")
								->with($this->equalTo($response->responseHeader));
		$this->responseBodyMock->expects($this->once())
								->method("set")
								->with($this->equalTo($response->response));
		
		$this->service->set($response);
	}
	
	/**
	* method getDocs
	* when called
	* should executesCorrectly
	*/
	public function test_getDocs_called_executesCorrectly()
	{
		$response = $this->getResponse();
		$expected = "adummyValue";
		
		$this->responseBodyMock->expects($this->once())
							  ->method("getDocs")
							  ->will($this->returnValue($expected));
		
		$this->service->set($response);
		$actual = $this->service->getDocs();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getNumDocs
	* when called
	* should returnNumDocsCorrectly
	*/
	public function test_getNumDocs_called_returnNumDocsCorrectly()
	{
		$response = $this->getResponse();
		$expected = 2012;
		$this->responseBodyMock->expects($this->once())
										->method("getNumDocs")
										->will($this->returnValue($expected));
		$this->service->set($response);
		$actual = $this->service->getNumDocs();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getQueryString
	* when called
	* should returnQueryStringCorrectly
	*/
	public function test_getQueryString_called_returnQueryStringCorrectly()
	{
		$response = $this->getResponse();
		$expected = "aDummyQuery";
		$this->responseHeaderMock->expects($this->once())
							  	 ->method("getQueryString")
							  	 ->will($this->returnValue($expected));
		
		$this->service->set($response);
		$actual = $this->service->getQueryString();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getPrevStart
	* when called
	* should returnCorrectPrevStart
	* @dataProvider DP_getPrevStart
	*/
	public function test_getPrevStart_called_returnCorrectPrevStart($start, $rows, $expected)
	{
		$this->responseHeaderMock->expects($this->once())
									->method("getRows")
									->will($this->returnValue($rows));
		$this->responseHeaderMock->expects($this->once())
									->method("getStart")
									->will($this->returnValue($start));
		
		$actual = $this->service->getPrevStart();
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getPrevStart()
	{
		return array(
				  		array(0, 10, false),
						array(8, 4, 4),
						array(-1, 4, 0),
						array(2, 4, 0)
					);
	}
	
	/**
	 * method getNextStart
	 * when called
	 * should returnCorrectNextStart
	 * @dataProvider DP_getNextStart
	 */
	public function test_getNextStart_called_returnCorrectNextStart($start, $rows, $numFound, $expected /* nextStart */)
	{
		$this->responseHeaderMock->expects($this->once())
									->method("getRows")
									->will($this->returnValue($rows));
		$this->responseHeaderMock->expects($this->once())
									->method("getStart")
									->will($this->returnValue($start));
		$this->responseBodyMock->expects($this->once())
								->method("getNumFound")
								->will($this->returnValue($numFound));
	
		$actual = $this->service->getNextStart();
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getNextStart()
	{
		return array(
				array(0, 10, 9, false),
				array(0, 10, 11, 10),
				array(10, 10, 11, false),
				array(-1, 10, 11, 0),
				array(50, 10, 50000, 60),
				array(20, 20, 105, 40)
		);
	}
	
	
	/**
	* method getRows
	* when called
	* should returnCorrectly
	*/
	public function test_getRows_called_returnCorrectly()
	{
		$expected = 50;
		$this->responseHeaderMock->expects($this->once())
									->method("getRows")
									->will($this->returnValue($expected));
		$actual = $this->service->getRows();
		$this->assertEquals($expected, $actual);
	}
	
	//exercises
	private function getResponse()
	{
		$response = new StdClass();
		$response->responseHeader = "aDummyStdClass";
		$response->response = "aDummyStdClass2";
		return $response;
	}
}

?>