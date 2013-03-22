<?php

require_once dirname(__FILE__).'/DB_DataObjectUtilsTests.php';
require_once dirname(__FILE__).'/../../../vufind/classes/Utils/DB_DataObject/EcontentHistoryDBUtils.php';

class EcontentHistoryDBUtilsTests extends DB_DataObjectUtilsTests
{
	
	public function setUp()
	{
		parent::setUp();	
		
		$this->dbObjectMock = $this->getMock("IDB_DataObjectUtils", array("find", "whereAdd", "update", "setRecordId", "free"));
		$className = $this->getClassDBUtilsName();
		$this->service = new $className($this->dbObjectMock);
	}
	
	public function getClassDBUtilsName()
	{
		return "EcontentHistoryDBUtils";
	}
	
	/**
	* method getClassName 
	* when called
	* should returnCorrectly
	*/
	public function test_getClassName_called_returnCorrectly()
	{
		$expected = "EContentHistoryEntry";
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
		
		$this->dbObjectMock->expects($this->once())
							->method("setRecordId")
							->with($this->equalTo($newRecordId));
		
		$this->dbObjectMock->expects($this->once())
							->method("whereAdd")
							->with($this->equalTo("recordId = ".$oldRecordId));
		
		$this->dbObjectMock->expects($this->once())
							->method("update");
		
		$this->service->changeRecordId($oldRecordId, $newRecordId);
	}	
}
?>