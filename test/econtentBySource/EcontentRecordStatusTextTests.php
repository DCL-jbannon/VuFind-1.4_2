<?php
require_once dirname(__FILE__).'/BaseHelperClassesTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/econtentBySource/EcontentRecordStatusText.php';

class EcontentRecordStatusTextTests extends BaseHelperClassesTests
{
	
	public function setUp()
	{
		parent::setUp();
		$this->service = new EcontentRecordStatusText($this->econtentRecordDetailsMock);
	}
	
	/**
	* method getString
	* when userIsNullAndRecordCanBeCheckOut
	* should returnCorrectString
	* @dataProvider commonDataProvider
	*/
	public function test_getStatus_userIsNullAndCanBeCheckOut_returnCorrectString($customMessage)
	{
		$expected = $this->getExpectedMessage($customMessage, EcontentRecordStatusText::availableOnline);
		$this->prepareIsCheckOutAvailable(true);
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getMsgAvailable")
										->will($this->returnValue($expected));
	
		$actual = $this->service->getString();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getString 
	* when userIsNullAndRecordCannotBeCheckOut
	* should returnCorrectString
	* @dataProvider commonDataProvider
	*/
	public function test_getString_userIsNullAndRecordCannotBeCheckOut_returnCorrectString($customMessage)
	{
		$expected = $this->getExpectedMessage($customMessage, EcontentRecordStatusText::checkedOut);
		$this->prepareIsCheckOutAvailable(false);
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getMsgCheckedOut")
										->will($this->returnValue($expected));
										
		$actual = $this->service->getString();
		$this->assertEquals($expected, $actual);
	}

	/**
	* method getString 
	* when userIsNotNullRecordCanBeCheckOutAndUserHasNotCheckedItOut
	* should returnCorrectString
	* @dataProvider commonDataProvider
	*/
	public function test_getString_userIsNotNullRecordCanBeCheckOutAndUserHasNotCheckedItOut_returnCorrectString($customMessage)
	{
		$patronId = "aDummyPatronId";
		$expected = $this->getExpectedMessage($customMessage, EcontentRecordStatusText::availableOnline);
		$this->prepareIsCheckOutAvailable(true);
		$this->prepareIsCheckedOutByPatron(false);
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getMsgAvailable")
										->will($this->returnValue($expected));
		
		$actual = $this->service->getString($this->userMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getString
	 * when userIsNotNullRecordCanNotBeCheckOutAndUserHasNotCheckedItOut
	 * should returnCorrectString
	 * @dataProvider commonDataProvider
	 */
	public function test_getString_userIsNotNullRecordCanNotBeCheckOutAndUserHasNotCheckedItOut_returnCorrectString($customMessage)
	{
		$patronId = "aDummyPatronId";
		$expected = $this->getExpectedMessage($customMessage, EcontentRecordStatusText::checkedOut);
		$this->prepareIsCheckOutAvailable(false);
		$this->prepareIsCheckedOutByPatron(false);
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getMsgCheckedOut")
										->will($this->returnValue($expected));
	
		$actual = $this->service->getString($this->userMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getString 
	* when userIsNotNullUserHasCheckedItOut
	* should returnCorrectString
	* @dataProvider commonDataProvider
	*/
	public function test_getString_userIsNotNullUserHasCheckedItOut_returnCorrectString($customMessage)
	{
		$patronId = "aDummyPatronId";
		$expected = $this->getExpectedMessage($customMessage, EcontentRecordStatusText::checkedOutToYou);
		$this->prepareIsCheckedOutByPatron(true);
		
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getMsgCheckedOutToYou")
										->will($this->returnValue($expected));
		
		$actual = $this->service->getString($this->userMock);
		$this->assertEquals($expected, $actual);
	}

	public function commonDataProvider()
	{
		return array(
					array(false), //This class that implements IEcontentRecordDetails wants to use the generic message
					array("a Dummy Custom Message Coming from a class that implements IEcontentRecordDetails")
				);
	}
	
	
	/**
	* method getUsageNotesMessage
	* when econtentDetailsReturnsFalse
	* should returnDefaultMessage
	*/
	public function test_getUsageNotesMessage_econtentDetailsReturnsFalse_returnDefaultMessage()
	{
		$expected = EcontentRecordStatusText::usageNote;
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getUsageNotesMessage")
										->will($this->returnValue(false));
		$actual = $this->service->getUsageNotesMessage();
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getUsageNotesMessage
	 * when econtentDetailsReturnsCustomMessage
	 * should returnDefaultMessage
	 */
	public function test_getUsageNotesMessage_econtentDetailsReturnsCustomMessage_returnDefaultMessage()
	{
		$expected = "aDummyUsageNoteMessage";
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getUsageNotesMessage")
										->will($this->returnValue($expected));
		$actual = $this->service->getUsageNotesMessage();
		$this->assertEquals($expected, $actual);
	}
	
		
	
	//PRIVATE PREPARES
	private function getExpectedMessage($customMessage, $genericMessage)
	{
		return ($customMessage===false ? $genericMessage : $customMessage);
	}
	
	
	private function prepareIsCheckOutAvailable($result)
	{
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("isCheckOutAvailable")
										->will($this->returnValue($result));
	}
	
	private function prepareIsCheckedOutByPatron($result)
	{
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("isCheckedOutByPatron")
										->will($this->returnValue($result));
	}
}
?>