<?php
require_once dirname(__FILE__).'/../../../vufind/classes/memcache/MemcacheWrapper.php';

class MemcacheWrapperTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	const key = "aDummyKey";	
	
	public function setUp()
	{
		global $configArray;
		$configArray['Caching']['memcache_host'] = 'localhost';
		$configArray['Caching']['memcache_port'] = '11211';
		$configArray['Caching']['memcache_connection_timeout'] = 1;		
		
		$this->service = new MemcacheWrapper();
		parent::setUp();		
	}
	
	/**
	* method setGet 
	* when called
	* should executesCorrectly
	*/
	public function test_setGet_called_executesCorrectly()
	{
		$expected = "aDummyValue";
		
		$result = $this->service->get(self::key);
		$this->assertFalse($result);
		
		$this->service->set(self::key, $expected, 0, 3600);
		$actual = $this->service->get(self::key);
		$this->assertEquals($expected, $actual);
	}
	
	public function tearDown()
	{
		$this->service->delete(self::key);
	}
		
}
?>