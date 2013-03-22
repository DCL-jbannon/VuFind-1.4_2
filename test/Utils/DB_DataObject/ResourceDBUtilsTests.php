<?php

require_once dirname(__FILE__).'/DB_DataObjectUtilsTests.php';
require_once dirname(__FILE__).'/../../../vufind/classes/Utils/DB_DataObject/ResourceDBUtils.php';

class ResourceDBUtilsTests extends DB_DataObjectUtilsTests
{
	
	public function setUp()
	{
		parent::setUp();	
		
		$this->dbObjectMock = $this->getMock("IDB_DataObjectUtils", array("find", "setRecordId", "update", "setResourceId"));
		$className = $this->getClassDBUtilsName();
		$this->service = new $className($this->dbObjectMock);
	}
	
	public function getClassDBUtilsName()
	{
		return "ResourceDBUtils";
	}
	
	/**
	* method getClassName 
	* when called
	* should returnCorrectly
	*/
	public function test_getClassName_called_returnCorrectly()
	{
		$expected = "Resource";
		$actual = $this->service->getClasDBName();
		$this->assertEquals($expected, $actual);
	}

	/**
	* method getByRecordId 
	* when doesNotExists
	* should returnFalse
	*/
	public function test_getByRecordId_doesNotExists_returnFalse()
	{
		$recordId = "aDummyRecordId";
		$this->prepareSetRecordId($recordId);
		$this->prepareFind(false);
		
		$actual = $this->service->getByRecordId($recordId);
		$this->assertFalse($actual);
	}
	
	/**
	 * method getByRecordId
	 * when exists
	 * should executesCorrectly
	 */
	public function test_getByRecordId_exists_executesCorrectly()
	{
		$recordId = "aDummyRecordId";
		
		$this->prepareSetRecordId($recordId);
		$this->prepareFind(true);
	
		$actual = $this->service->getByRecordId($recordId);
		$this->assertEquals($this->dbObjectMock, $actual);
	}
	
	/**
	 * method changeRecordIdByResourceId
	 * when called
	 * should executesCorrectly
	 */
	public function test_changeRecordIdByResourceId_called_executesCorrectly()
	{
		$resourceId = "aDummyOldId";
		$newRecordId = "aDummyNewId";
	
		$this->dbObjectMock->expects($this->at(0))
							->method("setResourceId")
							->with($this->equalTo($resourceId));
	
		$this->dbObjectMock->expects($this->at(1))
							->method("find")
							->with($this->equalTo(true));
	
		$this->dbObjectMock->expects($this->at(2))
							->method("setRecordId")
							->with($this->equalTo($newRecordId));
						
		$this->dbObjectMock->expects($this->at(3))
							->method("update");
	
		$actual = $this->service->changeRecordIdByResourceId($resourceId, $newRecordId);
		$this->assertEquals($this->dbObjectMock, $actual);
	}
	
	//privates
	private function prepareSetRecordId($recordId)
	{
		$this->dbObjectMock->expects($this->once())
							->method("setRecordId")
							->with($this->equalTo($recordId));
	}
	
	private function prepareFind($returnValue)
	{
		$this->dbObjectMock->expects($this->once())
							->method("find")
							->with($this->equalTo(true))
							->will($this->returnValue($returnValue));
	}
	
}
?>