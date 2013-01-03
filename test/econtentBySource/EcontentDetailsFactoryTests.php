<?php

require_once dirname(__FILE__).'/../../vufind/classes/econtentBySource/EcontentDetailsFactory.php';
class EcontentDetailsFactoryTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $econtentRecordMock;
		
	public function setUp()
	{
		$this->econtentRecordMock = $this->getMock("IEContentRecord", array("is3M", "find"));
		parent::setUp();		
	}
	
	/**
	* method get 
	* when econtentRecordIsNotEligibleToFactor
	* should returnFalse
	*/
	public function test_get_econtentRecordIsNotEligibleToFactor_returnFalse()
	{
		$this->econtentRecordMock->expects($this->once())
								 ->method("is3M")
								 ->will($this->returnValue(false));
		$actual = EcontentDetailsFactory::get($this->econtentRecordMock);
		$this->assertFalse($actual);
	}
	
	/**
	* method get 
	* when econtentIs3M
	* should returnThreemDetailsClass
	*/
	public function test_get_econtentIs3M_returnThreemDetailsClass()
	{
		$expected = "ThreemRecordDetails";
		$this->econtentRecordMock->expects($this->once())
									->method("is3M")
									->will($this->returnValue(true));
		$actual = EcontentDetailsFactory::get($this->econtentRecordMock);
		$this->assertEquals($expected, get_class($actual));
	}
	
	/**
	* method getById 
	* when notFound
	* should returnFalse
	*/
	public function test_getById_notFound_returnFalse()
	{
		$id = "aDummyId";
		$this->econtentRecordMock->id = "aDummyId";
		$this->econtentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(false));
		
		$actual = EcontentDetailsFactory::getById($id, $this->econtentRecordMock);
		$this->assertFalse($actual);
	}
	
	
	/**
	 * method getById
	 * when foundEcontentIs3M
	 * should 
	 */
	public function test_getById_foundEcontentIs3M_returnThreemDetailsClass()
	{
		$expected = "ThreemRecordDetails";
		$id = "aDummyId";
		$this->econtentRecordMock->id = "aDummyId";
		$this->econtentRecordMock->expects($this->once())
								->method("find")
								->with($this->equalTo(true))
								->will($this->returnValue(true));
	
		$this->econtentRecordMock->expects($this->once())
									->method("is3M")
									->will($this->returnValue(true));
		
		
		$actual = EcontentDetailsFactory::getById($id, $this->econtentRecordMock);
		$this->assertEquals($expected, get_class($actual));
	}
	
		
		
	
}
?>