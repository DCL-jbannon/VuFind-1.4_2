<?php

abstract class BaseEcontentDetailsTests extends PHPUnit_Framework_TestCase
{

	protected $service;
	protected $econtentRecordMock;
	
	public function setUp()
	{
		$this->econtentRecordMock = $this->getMock("IEContentRecord");
	}
	
	/**
	* method getStatusText 
	* when called
	* should returnEcontentRecordStatusTextClass
	*/
	public function test_getStatusText_called_returnEcontentRecordStatusTextClass()
	{
		$expected = "EcontentRecordStatusText";
		$actual = $this->service->getStatusText();
		$this->assertEquals($expected, get_class($actual));
	}
	
	/**
	 * method getShowButtons
	 * when called
	 * should returnEcontentRecordShowButtonsClass
	 */
	public function test_getShowButtons_called_returnEcontentRecordShowButtonsClass()
	{
		$expected = "EcontentRecordShowButtons";
		$actual = $this->service->getShowButtons();
		$this->assertEquals($expected, get_class($actual));
	}
	
	/**
	 * method getLinksInfo
	 * when called
	 * should returnEcontentRecordLinksClass
	 */
	public function test_getLinksInfo_called_returnEcontentRecordLinksClass()
	{
		$expected = "EcontentRecordLinks";
		$actual = $this->service->getLinksInfo();
		$this->assertEquals($expected, get_class($actual));
	}
	
	/**
	* method getRecordId 
	* when called
	* should returnCorrectly
	*/
	public function test_getRecordId_called_returnCorrectly()
	{
		$expected = "aDummyId";
		$this->econtentRecordMock->id = $expected;
		$actual = $this->service->getRecordId();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getCancelHoldUrls
	* when calledDefaultMethod
	* should returnAlwaysDefaultUrl
	*/
	public function test_getCancelHoldUrls_calledDefaultMethod_returnAlwaysDefaultUrl()
	{
		$econtentRecordId = "aDummyId";
		$expected = "/EcontentRecord/".$econtentRecordId."/CancelHold";
		$this->econtentRecordMock->id = $econtentRecordId;
		$actual = $this->service->getCancelHoldUrls();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getCheckOutUrls
	 * when calledDefaultMethod
	 * should returnAlwaysDefaultUrl
	 */
	public function test_getCheckOutUrls_calledDefaultMethod_returnAlwaysDefaultUrl()
	{
		$econtentRecordId = "aDummyId";
		$expected = "/EcontentRecord/".$econtentRecordId."/Checkout";
		$this->econtentRecordMock->id = $econtentRecordId;
		$actual = $this->service->getCheckOutUrls();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getSourceName 
	* when called
	* should returnSourceEcontentRecordValue
	*/
	public function test_getSourceName_called_returnSourceEcontentRecordValue()
	{
		$expected = "aDummySource";
		$this->econtentRecordMock->source = $expected;
		
		$actual = $this->service->getSourceName();
		$this->assertEquals($expected, $actual);
	}
	
		

}

?>