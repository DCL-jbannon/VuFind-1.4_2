<?php
require_once dirname(__FILE__).'/../../../vufind/classes/memcache/ClassCache.php';

class ClassCacheTests extends PHPUnit_Framework_TestCase
{
	private $memcacheServicesMock;
	private $classMock;
	private $service;
		
	public function setUp()
	{
		$this->classMock = $this->getMock("aDummyClass", array("existing_method"));
		$this->memcacheServicesMock = $this->getMock("IMemcacheServices", array("call"));
		
		$this->service = new ClassCache($this->classMock, NULL, $this->memcacheServicesMock);
		parent::setUp();		
	}

	/**
	* method methodDoesNotExists 
	* when called
	* should throw
	* @expectedException DomainException
	*/
	public function test_methodDoesNotExists_called_throw()
	{
		$this->service->non_existing_method();
	}
	
	/**
	* method existingMethod 
	* when methodExists
	* should executesCorrectly
	*/
	public function test_existingMethod_methodExists_executesCorrectly()
	{
		$expected = "aDummyResult";
		$this->memcacheServicesMock->expects($this->once())
									->method("call")
									->with($this->equalTo($this->classMock), $this->equalTo("existing_method"), 
										   $this->equalTo(array(1,2)),
										   $this->equalTo(NULL),	 
										   $this->equalTo(ClassCache::defaultTimeout) )
									->will($this->returnValue($expected));
		
		$this->service->existing_method(1,2);
	}
	
	/**
	 * method existingMethod
	 * when methodExistsSetKeyTimeout
	 * should executesCorrectly
	 */
	public function test_existingMethod_methodExistsSetKeyTimeout_executesCorrectly()
	{
		$expected = "aDummyResult";
		$this->memcacheServicesMock->expects($this->once())
									->method("call")
									->with($this->equalTo($this->classMock), $this->equalTo("existing_method"), $this->equalTo(array(1,2)), 
										   $this->equalTo("aDummyKey"), $this->equalTo("aDummyTimeout"))
									->will($this->returnValue($expected));
		
		$this->service->setKey("aDummyKey");
		$this->service->setTimeout("aDummyTimeout");
		$this->service->existing_method(1,2);
	}
	
	/**
	 * method existingMethod
	 * when methodExistsSetMiddleKey
	 * should executesCorrectly
	 */
	public function __test_existingMethod_methodExistsSetMiddleKey_executesCorrectly()
	{
		$expected = "aDummyResult";
		$keyExpected = get_class($this->classMock)."_aUniqueIdentifier_existing_method";
		
		$this->memcacheServicesMock->expects($this->once())
									->method("call")
									->with($this->equalTo($this->classMock), $this->equalTo("existing_method"), $this->equalTo(array(1,2)),
										   $this->equalTo($keyExpected), $this->equalTo("aDummyTimeout"))
									->will($this->returnValue($expected));
	
		$this->service->setKey("aDummyKey");
		$this->service->setTimeout("aDummyTimeout");
		$this->service->setUniqueKey("aUniqueIdentifier");
		$this->service->existing_method(1,2);
	}

}

?>