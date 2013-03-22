<?php
require_once dirname(__FILE__).'/../../vufind/classes/services/RecordServices.php';

class RecordServicesTests extends PHPUnit_Framework_TestCase
{
	private $resourceDAOMock;
	private $econtentDAORecordMock;
	private $service;
		
	public function setUp()
	{
		$this->resourceDAOMock = $this->getMock("IResourceDAO", array("getByRecordId"));
		$this->econtentDAORecordMock = $this->getMock("IEcontentRecordDAO", array("getByid"));
		
		$this->service = new RecordServices($this->resourceDAOMock, $this->econtentDAORecordMock);
		parent::setUp();		
	}
	
	/**
	* method getItem
	* when idIsPrintTitle
	* should executesCorrectly
	*/
	public function test_getItem_idIsPrintTitle_executesCorrectly()
	{
		$id = "aDummyId";
		$expected = "aDummyEntity";
		
		$this->resourceDAOMock->expects($this->once())
								->method("getByRecordId")
								->with($this->equalTo($id))
								->will($this->returnValue($expected));
		
		$actual = $this->service->getItem($id);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getItem
	 * when idIsEcontent
	 * should executesCorrectly
	 */
	public function test_getItem_idIsEcontent_executesCorrectly()
	{
		$id = "aDummyId";
		$idEcontent = EContentRecord::prefixUnique.$id;	
		$expected = "aDummyEntityEcontent";
	
		$this->econtentDAORecordMock->expects($this->once())
									->method("getByid")
									->with($this->equalTo($id))
									->will($this->returnValue($expected));
	
		$actual = $this->service->getItem($idEcontent);
		$this->assertEquals($expected, $actual);
	}
	
}
?>