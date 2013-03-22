<?php

require_once dirname(__FILE__).'/DB_DataObjectUtilsTests.php';
require_once dirname(__FILE__).'/../../../vufind/classes/Utils/DB_DataObject/UserRatingsDBUtils.php';

class UserRatingsDBUtilsTests extends DB_DataObjectUtilsTests
{
	
	public function setUp()
	{
		parent::setUp();	
		
		$this->dbObjectMock = $this->getMock("IDB_DataObjectUtils", array("find", "whereAdd", "update", "setResourceId", "fetch"));
		$className = $this->getClassDBUtilsName();
		$this->service = new $className($this->dbObjectMock);
	}
	
	public function getClassDBUtilsName()
	{
		return "UserRatingsDBUtils";
	}
	
	/**
	* method getClassName 
	* when called
	* should returnCorrectly
	*/
	public function test_getClassName_called_returnCorrectly()
	{
		$expected = "UserRating";
		$actual = $this->service->getClasDBName();
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method changeResourceId
	* when called
	* should executesCorrectly
	*/
	public function test_changeResourceId_called_executesCorrectly()
	{
		$oldResourceId = "aDummyOldId";
		$newResourceId = "aDummyNewId";
		
		$this->dbObjectMock->expects($this->at(0))
							->method("setResourceId")
							->with($this->equalTo($oldResourceId));
		
		$this->dbObjectMock->expects($this->at(1))
							->method("find");
	
		$this->dbObjectMock->expects($this->at(2))
							->method("fetch")
							->will($this->returnValue(true));
		
		$this->dbObjectMock->expects($this->at(3))
							->method("setResourceId")
							->with($this->equalTo($newResourceId));
		
		$this->dbObjectMock->expects($this->at(4))
							->method("update");
		
		$this->dbObjectMock->expects($this->at(5))
							->method("fetch")
							->will($this->returnValue(false));

		$this->service->changeResourceId($oldResourceId, $newResourceId);
	}	
	
		
	
	
}
?>