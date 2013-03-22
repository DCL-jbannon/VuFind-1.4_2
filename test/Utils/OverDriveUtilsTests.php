<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/OverDriveUtils.php';

class OverDriveUtilsTests extends PHPUnit_Framework_TestCase
{
	private $eContentRecordMock;
	
	public function setUp()
	{
		$this->eContentRecordMock = $this->getMock("IEContentRecord", array("find"));
		parent::setUp();
	}

	/**
	 * method getEcontentRecordFromOverDriveID
	 * when NotFound
	 * should returnFalse
	 */
	public function test_getEcontentRecordFromOverDriveID_NotFound_returnFalse()
	{
		$threemId = "aDummyOverDriveId";
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(false));
	
		$actual = OverDriveUtils::getEcontentRecordFromOverDriveID($threemId, $this->eContentRecordMock);
		$this->assertFalse($actual);
	}
	
	/**
	* method getEcontentRecordFromOverDriveID 
	* when called
	* should returnCorreclty
	*/
	public function test_getEcontentRecordFromOverDriveID_called_returnCorreclty()
	{
		$threemId = "aDummyOverDriveId";
		$this->eContentRecordMock->expects($this->once())
								 ->method("find")
								 ->with($this->equalTo(true))
								 ->will($this->returnValue(true));
		
		$actual = OverDriveUtils::getEcontentRecordFromOverDriveID($threemId, $this->eContentRecordMock);
		$this->assertEquals("http://www.emedia2go.org/ContentDetails.htm?ID=".$threemId, $this->eContentRecordMock->sourceUrl);
		$this->assertEquals($this->eContentRecordMock, $actual);
	}
	
		
	
}
?>