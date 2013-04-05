<?php
require_once dirname(__FILE__).'/../../../vufind/classes/memcache/MemcacheServices.php';

class MemcacheServicesTests extends PHPUnit_Framework_TestCase
{
	const method = "aDummyMethod";
	const className = "aDummyClass";
	
	private $service;
	private $memcacheWrapperMock;
	private $classMock;
		
	public function setUp()
	{
		$this->classMock = $this->getMock("aDummyClass", array(self::method));
		$this->memcacheWrapperMock = $this->getMock("IMemcacheWrapper", array("set","get", "delete"));

		$this->service = new MemcacheServices($this->memcacheWrapperMock);
		parent::setUp();		
	}

	/**
	* method delete 
	* when called
	* should executesCorrectly
	*/
	public function test_delete_called_executesCorrectly()
	{
		$key = "aDummyKey";
		$this->memcacheWrapperMock->expects($this->once())
									->method("delete")
									->with($this->equalTo($key));
		$actual = $this->service->delete($key);
	}
	
	/**
	* method set 
	* when called
	* should executesCorrectly
	*/
	public function test_set_called_executesCorrectly()
	{
		$key = "aDummyKey";
		$value = "aDummyValue";
		$timeout = "aDummyTimeOut";
		
		$this->memcacheWrapperMock->expects($this->once())
									->method("set")
									->with($this->equalTo($key),
										   $this->equalTo(serialize($value)), 
										   $this->equalTo(MemcacheServices::compress), 
										   $this->equalTo($timeout));
		
		$actual = $this->service->set($key, $value, $timeout);
		$this->assertTrue($actual);
	}

	/**
	* method call
	* when resultIsCached
	* should executesCorrectly
	*/
	public function test_call_resultIsCached_executesCorrectly()
	{
		
		$result = 's:12:"aDummyResult";';
		$expected = "aDummyResult";
		$this->prepareNeverCallMethodClass();
		$this->prepareGetMemcache($result, $this->getDefKey());
		
		$actual = $this->service->call($this->classMock, "aDummyMethod", array(1,2));
		$this->assertEquals($expected, $actual);
	}

	/**
	* method call 
	* when resultIsNotCached
	* should executeCorrectly
	*/
	public function test_call_resultIsNotCached_executeCorrectly()
	{
		$expectedSet = 's:12:"aDummyResult";';
		$expected = "aDummyResult";
		$this->prepareCallMethod($expected);
		$this->prepareGetMemcache(FALSE, $this->getDefKey());
		$this->prepareSet($this->getDefKey(), $expectedSet, MemcacheServices::defTimeout);
		
		$actual = $this->service->call($this->classMock, "aDummyMethod", array(1,2));
		$this->assertEquals($expected, $actual);
	}

	/**
	* method call 
	* when customKeyItIsCached
	* should executesCorrectly
	*/
	public function test_call_customKeyItIsCached_executesCorrectly()
	{
		$result = 's:12:"aDummyResult"';
		$expected = "aDummyResult";
		$key = "aDummyCustomKey";
		$this->prepareNeverCallMethodClass($expected);	
		$this->prepareGetMemcache($result, $key);
		
		$actual = $this->service->call($this->classMock, "aDummyMethod", array(1,2), $key);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * method call
	 * when customKeyTimeoutNotCached
	 * should executeCorrectly
	 */
	public function test_call_customKeyTimeoutNotCached_executeCorrectly()
	{
		$key = "aDummyCustomKey";
		$timeout = "aDummyCustomTimeout";
		$expected = "aDummyResult";
		$expectedSet = 's:12:"aDummyResult";';
		$this->prepareCallMethod($expected);
		$this->prepareGetMemcache(FALSE, $key);
		$this->prepareSet($key, $expectedSet, $timeout);
		
		$actual = $this->service->call($this->classMock, "aDummyMethod", array(1,2), $key, $timeout);
		$this->assertEquals($expected, $actual);
	}

	/**
	* method call 
	* when resultIsASimpleXML
	* should executeCorrectly
	*/
	public function test_call_resultIsASimpleXML_executeCorrectly()
	{
		$key = "aDummyCustomKey";
		$timeout = "aDummyCustomTimeout";
		$expected = new SimpleXMLElement("<tag>value</tag>");
		$expectedSetValue = array("__SimpleXMLElement"=>true, "xml"=>$expected->asXML());
		
		$this->prepareCallMethod($expected);
		$this->prepareGetMemcache(FALSE, $key);
		$this->prepareSet($key, serialize($expectedSetValue), $timeout);
		
		$actual = $this->service->call($this->classMock, "aDummyMethod", array(1,2), $key, $timeout);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * method call
	 * when getResultCacheIsSimpleXML
	 * should executeCorrectly
	 */
	public function test_call_getResultCacheIsSimpleXML_executeCorrectly()
	{
		$expected = new SimpleXMLElement("<tag>value</tag>");
		$expectedGetValue = serialize(array("__SimpleXMLElement"=>true, "xml"=>$expected->asXML()));
		
		$key = "aDummyCustomKey";
		$this->prepareNeverCallMethodClass();	
		$this->prepareGetMemcache($expectedGetValue, $key);
		
		$actual = $this->service->call($this->classMock, "aDummyMethod", array(1,2), $key);
		$this->assertEquals($expected, $actual);
	}

	//PRIVATE
	private function prepareSet($key, $value, $timeout)
	{
		$this->memcacheWrapperMock->expects($this->once())
									->method("set")
									->with($this->equalTo($key),
											$this->equalTo($value),
											$this->equalTo(MemcacheServices::compress),
											$this->equalTo($timeout));
	}

	private function prepareGetMemcache($result, $key)
	{
		$this->memcacheWrapperMock->expects($this->once())
								->method("get")
								->with($this->equalTo($key))
								->will($this->returnValue($result));
	}

	private function prepareNeverCallMethodClass()
	{
		$this->classMock->expects($this->never())
						->method(self::method);
	}

	private function prepareCallMethod($result)
	{
		$this->classMock->expects($this->once())
						->method(self::method)
						->with($this->equalTo(1), $this->equalTo(2))
						->will($this->returnValue($result));
	}

	private function getDefKey()
	{
		return get_class($this->classMock)."_".self::method;
	}
}
?>