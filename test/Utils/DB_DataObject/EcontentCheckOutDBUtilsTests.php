<?php

require_once dirname(__FILE__).'/DB_DataObjectUtilsTests.php';
require_once dirname(__FILE__).'/../../../vufind/classes/Utils/DB_DataObject/EcontentCheckOutDBUtils.php';

class EcontentCheckOutDBUtilsTests extends DB_DataObjectUtilsTests
{
	
	public function setUp()
	{
		parent::setUp();	
		
		$this->dbObjectMock = $this->getMock("IDB_DataObjectUtils", array("find", "whereAdd", "update", "setRecordId", "fetch"));
		$className = $this->getClassDBUtilsName();
		$this->service = new $className($this->dbObjectMock);
	}
	
	public function getClassDBUtilsName()
	{
		return "EcontentCheckOutDBUtils";
	}
	
	/**
	* method getClassName 
	* when called
	* should returnCorrectly
	*/
	public function test_getClassName_called_returnCorrectly()
	{
		$expected = "EContentCheckout";
		$actual = $this->service->getClasDBName();
		$this->assertEquals($expected, $actual);
	}

	/**
	* method changeRecordId
	* when called
	* should executesCorrectly
	*/
	public function test_changeRecordId_called_executesCorrectly()
	{
		$oldRecordId = "aDummyOldId";
		$newRecordId = "aDummyNewId";
		
		$this->dbObjectMock->expects($this->at(0))
							->method("setRecordId")
							->with($this->equalTo($oldRecordId));
		
		$this->dbObjectMock->expects($this->at(1))
							->method("find");
	
		$this->dbObjectMock->expects($this->at(2))
							->method("fetch")
							->will($this->returnValue(true));
		
		$this->dbObjectMock->expects($this->at(3))
							->method("setRecordId")
							->with($this->equalTo($newRecordId));
		
		$this->dbObjectMock->expects($this->at(4))
							->method("update");
		
		$this->dbObjectMock->expects($this->at(5))
							->method("fetch")
							->will($this->returnValue(false));

		$this->service->changeRecordId($oldRecordId, $newRecordId);
	}	
}
?>