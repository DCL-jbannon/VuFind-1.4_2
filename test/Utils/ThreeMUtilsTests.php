<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/ThreeMUtils.php';

class ThreeMUtilsTests extends PHPUnit_Framework_TestCase
{
	
	private $eContentRecordMock;
	
	public function setUp()
	{
		$this->eContentRecordMock = $this->getMock("IEContentRecord", array("find"));
		parent::setUp();
	}

	/**
	* method get3MId 
	* when called
	* should returnCorrectId
	*/
	public function test_get3MId_called_returnCorrectId()
	{
		$expected = "mnvz9";
		$this->eContentRecordMock->sourceUrl = "http://ebook.3m.com/library/DouglasCountyLibraries-document_id-".$expected;
		$actual = ThreeMUtils::get3MId($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getEcontentRecordFrom3MId
	 * when NotFound
	 * should returnFalse
	 */
	public function test_getEcontentRecordFrom3MId_NotFound_returnFalse()
	{
		$threemId = "aDummy3MId";
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(false));
	
		$actual = ThreeMUtils::getEcontentRecordFrom3MId($threemId, $this->eContentRecordMock);
		$this->assertFalse($actual);
	}
	
	/**
	* method getEcontentRecordFrom3MId 
	* when called
	* should returnCorreclty
	*/
	public function test_getEcontentRecordFrom3MId_called_returnCorreclty()
	{
		$threemId = "aDummy3MId";
		$this->eContentRecordMock->expects($this->once())
								 ->method("find")
								 ->with($this->equalTo(true))
								 ->will($this->returnValue(true));
		
		$actual = ThreeMUtils::getEcontentRecordFrom3MId($threemId, $this->eContentRecordMock);
		$this->assertEquals("http://ebook.3m.com/library/DouglasCountyLibraries-document_id-".$threemId, $this->eContentRecordMock->sourceUrl);
		$this->assertEquals($this->eContentRecordMock, $actual);
	}
	
		
	
}
?>