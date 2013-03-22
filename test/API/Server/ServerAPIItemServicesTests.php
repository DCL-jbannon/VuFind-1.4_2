<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Server/ServerAPIItemServices.php';

class ServerAPIItemServicesTests extends PHPUnit_Framework_TestCase
{
	private $service;
	private $recordServicesMock;
	private $recordDTOMock;
	
		
	public function setUp()
	{
		$this->recordServicesMock = $this->getMock("IRecordServices", array("getItem"));
		$this->recordDTOMock = $this->getMock("IRecordDTO", array("getDTO"));
		
		$this->service = new ServerAPIItemServices($this->recordServicesMock, $this->recordDTOMock);
		parent::setUp();		
	}
	
	/**
	 * method getItem
	 * when notFound
	 * should returnEmptyArray
	 */
	public function test_getItem_notFound_returnEmptyArray()
	{
		$id = "aDummyId";
		$result = BaseDAO::noResult();
		$expected = array();
	
		$this->prepareGetItem($id, $result);
	
		$this->recordDTOMock->expects($this->never())
							->method("getDTO");
	
		$actual = $this->service->getItemDetails($id);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getItem 
	* when called
	* should executesCorrectly
	*/
	public function test_getItem_called_executesCorrectly()
	{
		$id = "aDummyId";
		$record = "aDummyRecord"; //implements IGenericRecord
		$expected = "aDummyDTOResult";
		
		$this->prepareGetItem($id, $record);
	
		$this->recordDTOMock->expects($this->once())
							->method("getDTO")
							->with($this->equalTo($record))
							->will($this->returnValue($expected));
		
		$actual = $this->service->getItemDetails($id);
		$this->assertEquals($expected, $actual);
	}
	
	//prepares
	private function prepareGetItem($id, $result)
	{
		$this->recordServicesMock->expects($this->once())
									->method("getItem")
									->with($this->equalTo($id))
									->will($this->returnValue($result));
	}
}
?>