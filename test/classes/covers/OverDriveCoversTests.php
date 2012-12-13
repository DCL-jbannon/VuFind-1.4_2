<?php

require_once dirname(__FILE__).'/../../../vufind/classes/covers/OverDriveCovers.php';
require_once dirname(__FILE__).'/../../mother/OverDriveAPI/resultsMother.php';

class OverDriveCoversTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $eContentRecordMock;
	private $regularExpressionsMock;
	private $overDriveServicesMock;
	private $overDriveResultsMother;
	
	public function setUp()
	{
		$this->overDriveResultsMother = new OverDriveResultsMother();
		$this->overDriveServicesMock = $this->getMock("IOverDriveServicesAPI", array("getItemMetadata"));
		$this->regularExpressionsMock = $this->getMock("IRegularExpressions",array("getFieldValueFromURL"));
		$this->eContentRecordMock = $this->getMock("IEContentRecord", array("find","fetch", "isOverDrive"));
		$this->service = new OverDriveCovers($this->overDriveServicesMock, $this->regularExpressionsMock);
		parent::setUp();		
	}
	
	
	/**
	* method getImageUrl
	* when idNotFound
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_idNotFound_throw()
	{
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->will($this->returnValue(false));
		
		$id="aNonValidId";
		$this->service->getImageUrl($id, $this->eContentRecordMock);
	}
	
	/**
	* method getImageUrl
	* when calledWhenIdIsNotOverDriveRecord
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_calledWhenIdIsNotOverDriveRecord_throw()
	{

		$this->eContentRecordMock->source = "NotOverDriveSource";
		$this->eContentRecordMock->expects($this->once())
								 ->method("find")
								 ->will($this->returnValue(true));
		$this->eContentRecordMock->expects($this->once())
								->method("fetch");
		$this->eContentRecordMock->expects($this->once())
								 ->method("isOverDrive")
								 ->will($this->returnValue(false));

		$id="aDummyId";
		$this->service->getImageUrl($id, $this->eContentRecordMock);
	}

	/**
	* method getImageUrl
	* when OverDriveRecordNoInfo
	* should throw
	* @expectedException DomainException
	* @dataProvider DP_OverDriveSource
	*/
	public function test_getImageUrl_OverDriveRecordNoInfo_throw($source)
	{
		$this->eContentRecordMock->source = $source;
		$this->eContentRecordMock->sourceUrl = "aNonValidSourceOverDriveUrl";
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->will($this->returnValue(true));
		$this->eContentRecordMock->expects($this->once())
									->method("fetch");
		
		$this->eContentRecordMock->expects($this->once())
								->method("isOverDrive")
								->will($this->returnValue(true));
		
		$this->regularExpressionsMock->expects($this->once())
									 ->method("getFieldValueFromURL")
									 ->with($this->equalTo($this->eContentRecordMock->sourceUrl), $this->equalTo("ID"))
									 ->will($this->returnValue(""));
		
		$id="aDummyId";
		$this->service->getImageUrl($id, $this->eContentRecordMock);
	}
	
	/**
	 * method getImageUrl
	 * when isValidOverDriveRecord
	 * should returnCorrectImageUrl
	 * @dataProvider DP_OverDriveSource
	 */
	public function test_getImageUrl_isValidOverDriveRecord_returnCorrectImageUrl($source)
	{
		$overDriveResults = $this->overDriveResultsMother->getItemMetadataResult();
		$expected = "http://images.contentreserve.com/ImageType-100/0887-1/{30AF0828-3A80-4701-938F-D867930A0D88}Img100.jpg";
		$overDriveID = "aDummyOverDriveID";
		
		$this->eContentRecordMock->source = $source;
		$this->eContentRecordMock->sourceUrl = "aDummyValudUrl";
		$this->eContentRecordMock->expects($this->once())
								->method("find")
								->will($this->returnValue(true));
		$this->eContentRecordMock->expects($this->once())
								  ->method("fetch");
	
		$this->eContentRecordMock->expects($this->once())
									->method("isOverDrive")
									->will($this->returnValue(true));
		
		$this->regularExpressionsMock->expects($this->once())
										->method("getFieldValueFromURL")
										->with($this->equalTo($this->eContentRecordMock->sourceUrl), $this->equalTo("ID"))
										->will($this->returnValue($overDriveID));
	
		$this->overDriveServicesMock->expects($this->once())
									->method("getItemMetadata")
									->with($this->equalTo($overDriveID))
									->will($this->returnValue($overDriveResults));
		
		$id="aDummyId";
		$actual = $this->service->getImageUrl($id, $this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_OverDriveSource()
	{
		return array(array("OverDrive"),array("OverDriveAPI"));
					
	}

}

?>