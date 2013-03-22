<?php
require_once dirname(__FILE__).'/../../vufind/classes/services/EContentRecordServices.php';
require_once dirname(__FILE__).'/../mother/marcRecordMother.php';

class EContentRecordServicesTests extends PHPUnit_Framework_TestCase
{
	
	const eContentRecordFromId = "aDummyEcontentRecordFromId";
	const eContentRecordToId = "aDummyEcontentRecordToId";
	
	private $service;
	private $econtentRecordDbUtilsMock;
	private $econtentHistoryDbUtilsMock;
	private $econtentHoldDbUtilsMock;
	private $econtentCheckOutDbUtilsMock;
	private $resourceDBUtilsMock;
	private $commentsDBUtilsMock;
	private $userRatingsDBUtilsMock;
	
	private $resourceMock;
	
	public function setUp()
	{
		$this->econtentRecordDbUtilsMock   = $this->getMock("IDB_DataObjectUtils", array("getObjectById"));
		$this->econtentHistoryDbUtilsMock   = $this->getMock("IDB_DataObjectUtils", array("changeRecordId"));
		$this->econtentHoldDbUtilsMock     = $this->getMock("IDB_DataObjectUtils", array("changeRecordId"));
		$this->econtentCheckOutDbUtilsMock = $this->getMock("IDB_DataObjectUtils", array("changeRecordId"));
		$this->resourceDBUtilsMock = $this->getMock("IDB_DataObjectUtils", array("getByRecordId", "changeRecordIdByResourceId"));
		$this->commentsDBUtilsMock = $this->getMock("IDB_DataObjectUtils", array("changeResourceId"));
		$this->userRatingsDBUtilsMock = $this->getMock("IDB_DataObjectUtils", array("changeResourceId"));
		
		$this->resourceMock = $this->getMock("IResource");
		
		$this->service = new EContentRecordServices();
		parent::setUp();		
	}

	/**
	* method moveEcontentRelatedInformation 
	* when eContentRecordFromIdDoesNotExists
	* should throw
	* @expectedException DomainException
	*/
	public function test_moveEcontentRelatedInformation_eContentRecordFromIdDoesNotExists_throw()
	{
		$this->prepareCheckFromIdExists(false);
		$this->executeMove();
	}
	
	/**
	 * method moveEcontentRelatedInformation
	 * when eContentRecordToIdDoesNotExists
	 * should throw
	 * @expectedException DomainException
	 */
	public function test_moveEcontentRelatedInformation_eContentRecordToIdDoesNotExists_throw()
	{
		$this->prepareCheckFromIdExists("aDummyReturnValue");
		$this->prepareCheckToIdExists(false);
		$this->executeMove();
	}
	
	/**
	 * method moveEcontentRelatedInformation
	 * when oldRecordIdHasNoResourceEntry
	 * should executesCorrectly
	 */
	public function test_moveEcontentRelatedInformation_oldRecordIdHasNoResourceEntry_executesCorrectly()
	{
		$this->prepareCheckFromIdExists("aDummyReturnValue");
		$this->prepareCheckToIdExists("aDummyReturnValue");
		$this->prepareMockToChangeRecorId($this->econtentHistoryDbUtilsMock);
		$this->prepareMockToChangeRecorId($this->econtentHoldDbUtilsMock);
		$this->prepareMockToChangeRecorId($this->econtentCheckOutDbUtilsMock);
		$this->prepareGetResourceByRecordId(false, self::eContentRecordFromId, 0);
		$this->executeMove();
	}
	
	/**
	 * method moveEcontentRelatedInformation
	 * when OldRecordHasReourceButNewRecordIdHasNoResourceEntry
	 * should executesCorrectly
	 */
	public function test_moveEcontentRelatedInformation_OldRecordHasReourceButNewRecordIdHasNoResourceEntry_executesCorrectly()
	{	
		$resourceId = "aDummyResourceId";
		$this->resourceMock->id = $resourceId;
		
		$this->prepareCheckFromIdExists("aDummyReturnValue");
		$this->prepareCheckToIdExists("aDummyReturnValue");
		$this->prepareMockToChangeRecorId($this->econtentHistoryDbUtilsMock);
		$this->prepareMockToChangeRecorId($this->econtentHoldDbUtilsMock);
		$this->prepareMockToChangeRecorId($this->econtentCheckOutDbUtilsMock);
		$this->prepareGetResourceByRecordId($this->resourceMock, self::eContentRecordFromId, 0);
		$this->prepareGetResourceByRecordId(false, self::eContentRecordToId, 1);
		
		$this->resourceDBUtilsMock->expects($this->once())
									->method("changeRecordIdByResourceId")
									->with($this->equalTo($resourceId), self::eContentRecordToId);
		$this->executeMove();
	}
	
	/**
	 * method moveEcontentRelatedInformation
	 * when oldRecordHasReourceEntryNewRecordIdHasResourceEntry
	 * should executesCorrectly
	 */
	public function test_moveEcontentRelatedInformation_oldRecordHasReourceEntryNewRecordIdHasResourceEntry_executesCorrectly()
	{
		$resourceId = "aDummyResourceId";
		$resourceIdTo = "aDummyResourceIdTo";
		$this->resourceMock->id = $resourceId;
		
		$resourceMockTo = clone $this->resourceMock;
		$resourceMockTo->id = $resourceIdTo;
	
		$this->prepareCheckFromIdExists("aDummyReturnValue");
		$this->prepareCheckToIdExists("aDummyReturnValue");
		$this->prepareMockToChangeRecorId($this->econtentHistoryDbUtilsMock);
		$this->prepareMockToChangeRecorId($this->econtentHoldDbUtilsMock);
		$this->prepareMockToChangeRecorId($this->econtentCheckOutDbUtilsMock);
		$this->prepareGetResourceByRecordId($this->resourceMock, self::eContentRecordFromId, 0);
		$this->prepareGetResourceByRecordId($resourceMockTo, self::eContentRecordToId, 1);
	
		$this->commentsDBUtilsMock->expects($this->once())
									->method("changeResourceId")
									->with($this->equalTo($resourceId), $this->equalTo($resourceIdTo));
		
		$this->userRatingsDBUtilsMock->expects($this->once())
										->method("changeResourceId")
										->with($this->equalTo($resourceId), $this->equalTo($resourceIdTo));
		
		$this->executeMove();
	}
	

	
	//Executes
	
	private function prepareGetResourceByRecordId($returnValue, $parameterValue, $index = 0)
	{
		$this->resourceDBUtilsMock->expects($this->at($index))
									->method("getByRecordId")
									->with($this->equalTo($parameterValue))
									->will($this->returnValue($returnValue));
	}
	
	private function prepareMockToChangeRecorId($mokObject)
	{
		$mokObject->expects($this->once())
					->method("changeRecordId")
					->with($this->equalTo(self::eContentRecordFromId), $this->equalTo(self::eContentRecordToId));
	}
	
	
	private function prepareCheckFromIdExists($returnValue)
	{
		$this->econtentRecordDbUtilsMock->expects($this->at(0))
										->method("getObjectById")
										->with($this->equalTo(self::eContentRecordFromId))
										->will($this->returnValue($returnValue));
	}
	
	private function prepareCheckToIdExists($returnValue)
	{
		$this->econtentRecordDbUtilsMock->expects($this->at(1))
										->method("getObjectById")
										->with($this->equalTo(self::eContentRecordToId))
										->will($this->returnValue($returnValue));
	}
	
	
	private function executeMove()
	{
		return $this->service->moveEcontentRelatedInformation(	self::eContentRecordFromId,
																self::eContentRecordToId,
																$this->econtentRecordDbUtilsMock,
																$this->econtentHistoryDbUtilsMock,
																$this->econtentHoldDbUtilsMock,
																$this->econtentCheckOutDbUtilsMock,
																$this->resourceDBUtilsMock,
																$this->commentsDBUtilsMock,
																$this->userRatingsDBUtilsMock);
	}
}
?>