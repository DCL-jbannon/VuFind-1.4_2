<?php
require_once dirname(__FILE__).'/../mother/solrResponseMother.php';
require_once dirname(__FILE__).'/../../vufind/classes/SolrDriver.php';
require_once dirname(__FILE__).'/../../vufind/classes/services/IDCLReaderServices.php';

class IDCLReaderServicesTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $solrResponseMock;
	private $solrDriverMock;
	
	public function setUp()
	{
		$this->solrResponseMock = $this->getMock("ISolrResponse",array("getNumDocs","set"));
		$this->solrDriverMock = $this->getMock("ISolrDriver",array("search"));
		$this->service = new IDCLReaderServices($this->solrDriverMock, $this->solrResponseMock);
		parent::setUp();		
	}
	

	/**
	* method isValidEContent
	* when isNotValid
	* should returnFalse
	*/
	public function test_isValidEContent_isNotValid_returnFalse()
	{
		$id="aDummyId";
		$this->exerciseSolrDriverMockSearch("mysqlid:".$id);
		
		$this->solrResponseMock->expects($this->once())
								->method("getNumDocs")
								->will($this->returnValue(0));

		$actual = $this->service->isValidEContent($id);
		$this->assertFalse($actual);
	}
	
	/**
	 * method isValidEContent
	 * when isNotValid
	 * should returnTrue
	 */
	public function test_isValidEContent_isNotValid_returnTrue()
	{
		$id="aDummyId";
		$this->exerciseSolrDriverMockSearch("mysqlid:".$id);
		
		$this->solrResponseMock->expects($this->once())
								->method("getNumDocs")
								->will($this->returnValue(1));
		
		
		$actual = $this->service->isValidEContent($id);
		$this->assertTrue($actual);
	}
	
	/**
	* method isIOSPortalDeviceCompatible
	* when NotIphoneIpodIpad
	* should returnFalse
	* @dataProvider DP_isIOSPortalDevice_NotIphoneIpodIpad
	*/
	public function test_isIOSPortalDevice_NotIphoneIpodIpad_returnFalse($userAgent)
	{
		$_SERVER['HTTP_USER_AGENT'] = $userAgent;
		$actual = $this->service->isIOSPortalDeviceCompatible();
		$this->assertFalse($actual);
	}
	
	public function DP_isIOSPortalDevice_NotIphoneIpodIpad()
	{
		return array(
				  		array("User-Agent	Mozilla/5.0 (Windows NT 6.1; WOW64; rv:14.0) Gecko/20100101 Firefox/14.0.1"),
						array("Mozilla/5.0 (Linux; U; Android 2.2; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1"),
						array("Mozilla/5.0 (Linux; U; Android 2.1-update1; de-de; HTC Desire 1.19.161.5 Build/ERE27) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17")
					);
	}
	
	/**
	 * method isIOSPortalDeviceCompatible
	 * when IphoneIpodIpad
	 * should returnTrue
	 * @dataProvider DP_isIOSPortalDevice_IphoneIpodIpad
	 */
	public function test_IphoneIpodIpad_called_returnTrue($userAgent)
	{
		$_SERVER['HTTP_USER_AGENT'] = $userAgent;
		$actual = $this->service->isIOSPortalDeviceCompatible();
		$this->assertTrue($actual);
	}
	
	public function DP_isIOSPortalDevice_IphoneIpodIpad()
	{
		return array(
				array("Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3"),
				array("Mozilla/5.0 (iPod; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3A101a Safari/419.3"),
				array("Mozilla/5.0 (User-Agent	Mozilla/5.0 (iPad; U; CPU OS 4_2_1 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5")
		);
	}
	
	//exercise
	private function exerciseSolrDriverMockSearch($query)
	{
		$returnedValue = new stdClass();
		$this->solrDriverMock->expects($this->once())
								->method("search")
								->with($this->equalTo($query))
								->will($this->returnValue($returnedValue));
		$this->solrResponseMock->expects($this->once())
								->method("set")
								->with($this->equalTo($returnedValue));
	}
	
}

?>