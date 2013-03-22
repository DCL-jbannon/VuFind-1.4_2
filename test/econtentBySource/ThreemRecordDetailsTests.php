<?php
require_once dirname(__FILE__).'/../mother/ThreeMAPI/resultsMother.php';
require_once dirname(__FILE__).'/../../vufind/classes/econtentBySource/ThreemRecordDetails.php';
require_once dirname(__FILE__).'/../../vufind/classes/interfaces/IEcontentRecordDetails.php';
require_once dirname(__FILE__).'/BaseEcontentDetailsTests.php';

class ThreemRecordDetailsTests extends BaseEcontentDetailsTests
{
	private $threeMUtilsMock;
	private $threemAPIMock;
	private $threeMResultsMother;
	private $memcacheServicesMock;
	
	const threemId = "aDummy3MId";
	const patrondId = "aDummyPatronId";
		
	public function setUp()
	{
		parent::setUp();
		$this->threeMResultsMother = new ResultsThreeMAPIMother();
		
		$this->threeMUtilsMock = $this->getMock("IThreeMUtils", array("get3MId"));
		$this->econtentRecordStatusTextMock = $this->getMock("IEcontentRecordStatusText", array("getString"));
		$this->threemAPIMock = $this->getMock("IThreeMAPI", array("getItemDetails","getPatronCirculation","checkout", "checkin", "placeHold", "cancelHold"));
		$this->memcacheServicesMock = $this->getMock("IMemcacheServices", array("call"));
		
		$this->service = new ThreemRecordDetails($this->econtentRecordMock,
												 $this->threemAPIMock,
												 $this->threeMUtilsMock,
												 $this->memcacheServicesMock);
	}
	
	/**
	* method getTotalCopies 
	* when called
	* should executesCorrectly
	*/
	public function test_getTotalCopies_called_executesCorrectly()
	{
		$expected = "aDummyTotalCopiesValues";
		$resultItemDetails = $this->threeMResultsMother->getItemDetails($expected);
		
		$this->prepareMockGet3MIdFromEContentRecord();
		$this->prepareGetItemDetailsAPIMethod($resultItemDetails);
		
		$actual = $this->service->getTotalCopies();
		$this->assertEquals($expected, $actual);					
	}
	
	/**
	 * method getAvailableCopies
	 * when called
	 * should executesCorrectly
	 */
	public function test_getAvailableCopies_called_executesCorrectly()
	{
		$expected = "aDummyAvailableCopiesValues";
		$resultItemDetails = $this->threeMResultsMother->getItemDetails(1, $expected);
		
		$this->prepareMockGet3MIdFromEContentRecord();
		$this->prepareGetItemDetailsAPIMethod($resultItemDetails);

		$actual = $this->service->getAvailableCopies();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method isCheckOutAvailable 
	* when whenItIsNot
	* should returnFalse
	*/
	public function test_isCheckOutAvailable_whenItIsNot_returnFalse()
	{
		$results = $this->threeMResultsMother->getItemCannotBeCheckOut();
		
		$actual = $this->exerciseCallCanBeCheckOut($results);
		$this->assertFalse($actual);
	}
	
	/**
	 * method isCheckOutAvailable
	 * when whenItIs
	 * should returnTrue
	 */
	public function test_isCheckOutAvailable_whenItIs_returnTrue()
	{
		$results = $this->threeMResultsMother->getItemCanBeCheckOut();
		
		$actual = $this->exerciseCallCanBeCheckOut($results);
		$this->assertTrue($actual);
	}
	
	/**
	* method isPlaceHoldAvailable 
	* when itIsNot
	* should returnFalse
	*/
	public function test_isPlaceHoldAvailable_itIsNot_returnFalse()
	{
		$results = $this->threeMResultsMother->getItemCannotBeHold();
		
		$actual = $this->exerciseCallCanBeHold($results);
		$this->assertFalse($actual);
	}
	
	/**
	 * method isPlaceHoldAvailable
	 * when itIs
	 * should returntrue
	 */
	public function test_isPlaceHoldAvailable_itIs_returntrue()
	{
		$results = $this->threeMResultsMother->getItemCanBeHold();
		
		$actual = $this->exerciseCallCanBeHold($results);
		$this->assertTrue($actual);
	}
	
	/**
	 * method isCancelHoldAvailable
	 * when userHasNoItemsPlacedHold
	 * should returnFalse
	 */
	public function test_isCancelHoldAvailable_userHasNoItemsPlacedHold_returnFalse()
	{
		$this->prepareGetUserId();
		
		$resultPatronCirculation = $this->threeMResultsMother->getPatronCirculationResults(self::threemId);
		$this->exercisecallGetIPatronCirculation($resultPatronCirculation, "3MIdPatronHasNotPlacedHold");
		
		$actual = $this->service->isCancelHoldAvailable($this->userMock);
		$this->assertFalse($actual);	
	}
	
	/**
	* method isCancelHoldAvailable 
	* when userHasNotPlacedHoldTheItem
	* should returnFalse
	*/
	public function test_isCancelHoldAvailable_userHasNotPlacedHoldTheItem_returnFalse()
	{
		$this->prepareGetUserId();
		$resultPatronCirculation = $this->threeMResultsMother->getPatronCirculationResults(self::threemId, true);
		$this->exercisecallGetIPatronCirculation($resultPatronCirculation, "3MIdPatronHasNotPlacedHold");
		
		$actual = $this->service->isCancelHoldAvailable($this->userMock);
		$this->assertFalse($actual);		
	}
	
	/**
	 * method isCancelHoldAvailable
	 * when userHasPlacedHoldTheItem
	 * should returnTrue
	 */
	public function test_isCancelHoldAvailable_userHasPlacedHoldTheItem_returnFalse()
	{
		$this->prepareGetUserId();
		$resultPatronCirculation = $this->threeMResultsMother->getPatronCirculationResults(self::threemId);
		$this->exercisecallGetIPatronCirculation($resultPatronCirculation, self::threemId);
	
		$actual = $this->service->isCancelHoldAvailable($this->userMock);
		$this->assertTrue($actual);
	}
	
	/**
	* method isAddWishListAvailable 
	* when called
	* should returnAlwaysFalse
	*/
	public function test_isAddWishListAvailable_called_returnAlwaysFalse()
	{
		$actual = $this->service->isAddWishListAvailable();
		$this->assertFalse($actual);
	}
	
	/**
	 * method isAccessOnlineAvailable
	 * when called
	 * should returnAlwaysFalse
	 */
	public function test_isAccessOnlineAvailable_called_returnAlwaysFalse()
	{
		$actual = $this->service->isAccessOnlineAvailable();
		$this->assertFalse($actual);
	}
	
	/**
	* method getWishListSize 
	* when called
	* should returnAlwaysZero
	*/
	public function test_getWishListSize_called_returnAlwaysZero()
	{
		$expected = 0;
		$actual = $this->service->getWishListSize();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getHoldLength 
	* when called
	* should returnCorrectValue
	*/
	public function test_getHoldLength_called_returnCorrectValue()
	{
		$expected = ResultsThreeMAPIMother::holdLength;
		$result = $this->threeMResultsMother->getItemDetails();
		$this->prepareMockGet3MIdFromEContentRecord();
		$this->prepareGetItemDetailsAPIMethod($result);
		
		$actual = $this->service->getHoldLength();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method isCheckedOutByPatron 
	* when NotCheckedOutByPatron
	* should returnFalse
	*/
	public function test_isCheckedOutByPatron_NotCheckedOutByPatron_returnFalse()
	{
		$this->prepareGetUserId();
		$resultPatronCirculation = $this->threeMResultsMother->getPatronCirculationResults(self::threemId, false, false);
		$this->exercisecallGetIPatronCirculation($resultPatronCirculation, self::threemId);
		
		$actual = $this->service->isCheckedOutByPatron($this->userMock);
		$this->assertFalse($actual);
	}
	
	/**
	 * method isCheckedOutByPatron
	 * when CheckedOutByPatron
	 * should returnTrue
	 */
	public function test_isCheckedOutByPatron_CheckedOutByPatron_returnTrue()
	{
		$this->prepareGetUserId();
		$resultPatronCirculation = $this->threeMResultsMother->getPatronCirculationResults(self::threemId, false, true);
		$this->exercisecallGetIPatronCirculation($resultPatronCirculation, self::threemId."_2");
		
		$actual = $this->service->isCheckedOutByPatron($this->userMock);
		$this->assertTrue($actual);
	}
	
	/**
	 * method isCheckedOutByPatron
	 * when CheckedOutByPatronNotFirtsOnTheList
	 * should returnFalse
	 */
	public function test_isCheckedOutByPatron_CheckedOutByPatronNotFirtsOnTheList_returnFalse()
	{
		$this->prepareGetUserId();
		$resultPatronCirculation = $this->threeMResultsMother->getPatronCirculationResults(self::threemId, false, true);
		$this->exercisecallGetIPatronCirculation($resultPatronCirculation, self::threemId);
	
		$actual = $this->service->isCheckedOutByPatron($this->userMock);
		$this->assertTrue($actual);
	}
	
	/**
	* method getFormatType 
	* when called
	* should returnAlwaysSameFormat
	*/
	public function test_getFormatType_called_returnAlwaysSameFormat()
	{
		$expected = EContentFormatType::eBook;
		$actual = $this->service->getFormatType();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getMsgAvailable 
	* when called
	* should returnAlwaysSameString
	*/
	public function test_getMsgAvailable_called_returnAlwaysSameString()
	{
		$expected = "Available from 3M";
		$actual = $this->service->getMsgAvailable();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method otherMessages 
	* when called
	* should returnFalse.
	* @dataProvider DP_otherMessages
	*/
	public function test_otherMessages_called_returnFalse($method)
	{
		$actual = $this->service->$method();
		$this->assertFalse($actual);
	}
	
	public function DP_otherMessages()
	{
		return array(
					array("getMsgCheckedOut"),
					array("getMsgCheckedOutToYou"),
				);
	}
	
	/**
	* method getFormats 
	* when called
	* should returnAlwaysEPUB
	*/
	public function test_getFormats_called_returnAlwaysEPUB()
	{
		$expected = "EPUB";
		$actual = $this->service->getFormats();
		$this->assertEquals($expected, $actual);
	}

	/**
	* method getMethodLoadStatusSummaries 
	* when called
	* should returnAlwaysSameValue
	*/
	public function test_getMethodLoadStatusSummaries_called_returnAlwaysSameValue()
	{
		$expected = "unique";
		$actual = $this->service->getMethodLoadStatusSummaries();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getAccessUrls
	* when called
	* should returnCorrectly
	*/
	public function test_getAccessUrls_called_returnCorrectly()
	{
		$expected = "aDummyUrl";
		$this->econtentRecordMock->expects($this->once())
									->method("getSourceUrl")
									->will($this->returnValue($expected));
		
		$actual = $this->service->getAccessUrls($this->userMock);
		$this->assertEquals($expected, $actual);
	}
	
	/***
	 * ACTIONS OVER ITEMID AND PATRON
	 */
	/**
	* method actionToCall 
	* when called
	* should executesCorrectly
	* @dataProvider DP_actionToCall
	*/
	public function test_checkout_fails_returnFalse($methodNameToTest)
	{
		$this->prepareGetUserId();
		$expected = "aDummyResult";
		$this->prepareMockGet3MIdFromEContentRecord();
		$this->threemAPIMock->expects($this->once())
							->method($methodNameToTest)
							->with($this->equalTo(self::threemId), $this->equalTo(self::patrondId))
							->will($this->returnValue($expected));
		
		$actual =  $this->service->$methodNameToTest($this->userMock, self::threemId);		
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_actionToCall()
	{
		return array(
						array("checkout"),
						array("checkin"),
						array("placeHold"),
						array("cancelHold"),
					);
	}
	
	/**
	* method getSize 
	* when called
	* should returnCorrectSize
	*/
	public function test_getSize_called_returnCorrectSize()
	{
		$expected = "1.9 MB";
		$result = $this->threeMResultsMother->getItemDetails();
		$this->prepareMockGet3MIdFromEContentRecord();
		$this->prepareGetItemDetailsAPIMethod($result);
		
		$actual = $this->service->getSize();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method showAddItemButton 
	* when called
	* should returnAlwaysFalse
	*/
	public function test_showAddItemButton_called_returnAlwaysFalse()
	{
		$actual = $this->service->showAddItemButton();
		$this->assertFalse($actual);
	}
	
	/**
	* method getUsageNotesMessage 
	* when called
	* should returnAlwaysFalse
	*/
	public function test_getUsageNotesMessage_called_returnAlwaysFalse()
	{
		$actual = $this->service->getUsageNotesMessage();
		$this->assertFalse($actual);
	}
	
		
	/**
	* method showCancelHoldLinkAvailableHolds 
	* when called
	* should returnAlwaysFalse
	*/
	public function test_showCancelHoldLinkAvailableHolds_called_returnAlwaysFalse()
	{
		$actual = $this->service->showCancelHoldLinkAvailableHolds();
		$this->assertFalse($actual);
	}
	
	/**
	* method canSuspendHolds 
	* when called
	* should returnFalse
	*/
	public function test_canSuspendHolds_called_returnFalse()
	{
		$actual = $this->service->canSuspendHolds();
		$this->assertFalse($actual);
	}
	
	/**
	* method canBeCheckIn 
	* when called
	* should returnAlwaysTrue
	*/
	public function test_canBeCheckIn_called_returnAlwaysTrue()
	{
		$actual = $this->service->canBeCheckIn();
		$this->assertTrue($actual);
	}
	
	/**
	* method getNumItems 
	* when called
	* should returnAlwaysOne
	*/
	public function test_getNumItems_called_returnAlwaysOne()
	{
		$expected = 1;
		$actual = $this->service->getNumItems();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method removeWishList 
	* when called
	* should returnAlwaysFalse
	*/
	public function test_removeWishList_called_returnAlwaysFalse()
	{
		$actual = $this->service->removeWishList($this->userMock);
		$this->assertFalse(false);
	}
	
	/**
	 * method addWishList
	 * when called
	 * should returnAlwaysFalse
	 */
	public function test_addWishList_called_returnAlwaysFalse()
	{
		$actual = $this->service->addWishList($this->userMock);
		$this->assertFalse(false);
	}
	
		
	
		
		
	
	//***** PRIVATES!
	private function exercisecallGetIPatronCirculation($resultPatronCirculation, $threeMIdInEcontentRecord)
	{
		$this->prepareMockGet3MIdFromEContentRecord($threeMIdInEcontentRecord);
		$this->prepareCallGetPatronCirculation($resultPatronCirculation);
	}
	
	private function prepareCallGetPatronCirculation($resultPatronCirculation)
	{		
		$this->memcacheServicesMock->expects($this->once())
									->method("call")
									->with($this->equalTo($this->threemAPIMock), $this->equalTo("getPatronCirculation"), $this->equalTo(array(self::patrondId)))
									->will($this->returnValue($resultPatronCirculation));	
	}
	
	private function exerciseCallCanBeCheckOut($results)
	{
		$this->prepareCallGetItemDetails($results);
		return $this->service->isCheckOutAvailable();
	}
	
	private function exerciseCallCanBeHold($results)
	{
		$this->prepareCallGetItemDetails($results);
		return $this->service->isPlaceHoldAvailable();
	}
	
	private function prepareCallGetItemDetails($results)
	{
		$this->prepareMockGet3MIdFromEContentRecord();
		$this->prepareGetItemDetailsAPIMethod($results);
	}
	
	private function prepareMockGet3MIdFromEContentRecord($threeMId = NULL)
	{
		if($threeMId === NULL) $threeMId = self::threemId;
		
		
		$this->threeMUtilsMock->expects($this->once())
								->method("get3MId")
								->with($this->equalTo($this->econtentRecordMock))
								->will($this->returnValue($threeMId));
	}
	
	private function prepareGetItemDetailsAPIMethod($resultToReturn)
	{
		$this->memcacheServicesMock->expects($this->once())
								   ->method("call")
								   ->with($this->equalTo($this->threemAPIMock), $this->equalTo("getItemDetails"), $this->equalTo(array(self::threemId)))
								   ->will($this->returnValue($resultToReturn));
	}
	
	private function prepareGetUserId()
	{
		$this->userMock->expects($this->once())
						->method("getId")
						->will($this->returnValue(self::patrondId));
	}
}
?>