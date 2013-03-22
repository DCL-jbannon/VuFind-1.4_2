<?php

abstract class DB_DataObjectUtilsTests extends PHPUnit_Framework_TestCase
{
	
	abstract public function getClassDBUtilsName();

	protected $service;
	protected $dbObjectMock;
	
	public function setUp()
	{
		$this->dbObjectMock = $this->getMock("IDB_DataObjectUtils", array("find", "whereAdd", "update", "free"));
		$className = $this->getClassDBUtilsName();
		$this->service = new $className($this->dbObjectMock);
		parent::setUp();
	}
		
	/**
	 * method getObjectById
	 * when objectDoesNotExists
	 * should returnFalse
	 */
	public function test_getObjectById_objectDoesNotExists_returnFalse()
	{
		$id = "aDummyObjectId";
		$this->dbObjectMock->expects($this->once())
							->method("find")
							->with($this->equalTo(true))
							->will($this->returnValue(false));
	
		$actual = $this->service->getObjectById($id);
		$this->assertFalse($actual);
	}
	
	/**
	 * method getObjectById
	 * when objectExists
	 * should returnObjectInstance
	 */
	public function test_getObjectById_objectExists_returnObjectInstance()
	{
		$id = "aDummyObjectId";
		$this->dbObjectMock->expects($this->once())
						->method("find")
						->with($this->equalTo(true))
						->will($this->returnValue(true));
		
		$actual = $this->service->getObjectById($id);
		$this->assertEquals($id, $actual->id);
	}
	
	
	
}

?>