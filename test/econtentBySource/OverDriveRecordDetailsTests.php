<?php
require_once dirname(__FILE__).'/../../vufind/classes/econtentBySource/OverDriveRecordDetails.php';
require_once dirname(__FILE__).'/../../vufind/classes/interfaces/IEcontentRecordDetails.php';
require_once dirname(__FILE__).'/../mother/OverDriveAPI/resultsMother.php';
require_once dirname(__FILE__).'/BaseEcontentDetailsTests.php';

class OverDriveRecordDetailsTests extends BaseEcontentDetailsTests
{
	private $odAPIServicesMock;
	private $regExpMock;
	private $overDriveMother;
	
	const sourceUrlValue = "aDummyUrl";
	const overDriveId = "aDummyOverDriveId";
	const overDriveIdCapitalLetters = "ADUMMYOVERDRIVEID";
	const username = "aDummyUsername";
		
	public function setUp()
	{	
		parent::setUp();
		
		$this->overDriveMother = new OverDriveResultsMother();
		$this->regExpMock = $this->getMock("IRegularExpressions", array("getFieldValueFromURL"));
		
		$this->odAPIServicesMock = $this->getMock("IOverDriveServicesAPI", array("getItemDetails", "getPatronCirculation", "checkOut",
																			     "checkin", "placeHold", "cancelHold", "getItemMetadata",
				                                                                 "removeWishList", "addToWishList", "chooseFormat"));

		$this->service = new OverDriveRecordDetails($this->econtentRecordMock, $this->odAPIServicesMock, 
																			   $this->regExpMock);
	}

	/**
	* method isCheckOutAvailable 
	* when notAvailable
	* should returnsCorrectly
	*/
	public function test_isCheckOutAvailable_notAvailable_returnFalse()
	{
		$result = $this->overDriveMother->getItemCheckedOutStatus(false);
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
		
		$actual = $this->service->isCheckOutAvailable();
		$this->assertFalse($actual);
	}
	
	/**
	 * method isCheckOutAvailable
	 * when available
	 * should returnTrue
	 */
	public function test_isCheckOutAvailable_available_returnTrue()
	{
		$result = $this->overDriveMother->getItemCheckedOutStatus(true);
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
	
		$actual = $this->service->isCheckOutAvailable();
		$this->assertTrue($actual);
	}
	
	/**
	 * method isPlaceHoldAvailable
	 * when notAvailable
	 * should returnsCorrectly
	 */
	public function test_isPlaceHoldAvailable_notAvailable_returnFalse()
	{
		$result = $this->overDriveMother->getItemPlaceHoldStatus(false);
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
	
		$actual = $this->service->isPlaceHoldAvailable();
		$this->assertFalse($actual);
	}
	
	/**
	 * method isPlaceHoldAvailable
	 * when available
	 * should returnTrue
	 */
	public function test_isPlaceHoldAvailable_available_returnTrue()
	{
		$result = $this->overDriveMother->getItemPlaceHoldStatus(true);
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
	
		$actual = $this->service->isPlaceHoldAvailable();
		$this->assertTrue($actual);
	}
	
	/**
	 * method isAddWishListAvailable
	 * when notAvailable
	 * should returnsCorrectly
	 */
	public function test_isAddWishListAvailable_notAvailable_returnFalse()
	{
		$result = $this->overDriveMother->getItemWishListStatus(false);
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
	
		$actual = $this->service->isAddWishListAvailable();
		$this->assertFalse($actual);
	}
	
	/**
	 * method isAddWishListAvailable
	 * when available
	 * should returnsCorrectly
	 */
	public function test_isAddWishListAvailable_available_returnFalse()
	{
		$result = $this->overDriveMother->getItemWishListStatus(true);
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
	
		$actual = $this->service->isAddWishListAvailable();
		$this->assertTrue($actual);
	}
	
	/**
	 * method isAccessOnlineAvailable
	 * when called
	 * should returnAlwaysFalse
	 */
	public function test_isAddWishListAvailable_called_returnAlwaysFalse()
	{
		$actual = $this->service->isAccessOnlineAvailable();
		$this->assertFalse($actual);
	}

	/**
	* method getTotalCopies 
	* when called
	* should executesCorrectly
	*/
	public function test_getTotalCopies_called_executesCorrectly()
	{
		$expected = OverDriveResultsMother::totalCopies;
		$result = $this->overDriveMother->getItemDetails();
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
		
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
		$expected = OverDriveResultsMother::availableCopies;
		$result = $this->overDriveMother->getItemDetails();
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
	
		$actual = $this->service->getAvailableCopies();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getHoldLength
	 * when called
	 * should executesCorrectly
	 */
	public function test_getHoldLength_called_executesCorrectly()
	{
		$expected = OverDriveResultsMother::onHold;
		$result = $this->overDriveMother->getItemDetails();
		$this->prepareGetOverDriveID();
		$this->prepareCallGetItemDetailsCache($result);
	
		$actual = $this->service->getHoldLength();
		$this->assertEquals($expected, $actual);
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
	* method isCancelHoldAvailable 
	* when userHasNotPlaceHoldItem
	* should returnFalse
	*/
	public function test_isCancelHoldAvailable_userHasNotPlaceHoldItem_returnFalse()
	{
		$patronCirculation = $this->overDriveMother->getPatronCirculation();
		$overDriveId = "aNonOverDriveItemPlaceByUser";
		$this->prepareGetOverDriveID();
		$this->prepareGetUserUsername();
		$this->prepareGetPatronCirculation($patronCirculation);
		
		$actual = $this->service->isCancelHoldAvailable($this->userMock);
		$this->assertFalse($actual);				
	}
	
	/**
	 * method isCancelHoldAvailable
	 * when userHasPlaceHoldItem
	 * should returnFalse
	 */
	public function test_isCancelHoldAvailable_userHasPlaceHoldItem_returnFalse()
	{
		$patronCirculation = $this->overDriveMother->getPatronCirculation();
		$overDriveId = "82CDD641-857A-45CA-8775-34EEDE35B238";
		$this->prepareGetOverDriveID($overDriveId);
		$this->prepareGetUserUsername();
		$this->prepareGetPatronCirculation($patronCirculation);
	
		$actual = $this->service->isCancelHoldAvailable($this->userMock);
		$this->assertTrue($actual);
	}
	
	
	/**
	 * method isCheckedOutByPatron
	 * when itIsNot
	 * should returnFalse
	 */
	public function test_isCheckedOutByPatron_itIsNot_returnFalse()
	{
		$patronCirculation = $this->overDriveMother->getPatronCirculation();
		$overDriveId = "aNonOverDriveItemCheckedOutByUser";
		$this->prepareGetOverDriveID();
		$this->prepareGetUserUsername();
		$this->prepareGetPatronCirculation($patronCirculation);
	
		$actual = $this->service->isCheckedOutByPatron($this->userMock);
		$this->assertFalse($actual);
	}
	
	/**
	 * method isCheckedOutByPatron
	 * when Itis
	 * should returnFalse
	 */
	public function test_isCheckedOutByPatron_ItIs_returnFalse()
	{
		$patronCirculation = $this->overDriveMother->getPatronCirculation();
		$overDriveId = "EA87339B-9B92-423E-B413-D9A17BF33AF9";
		$this->prepareGetOverDriveID($overDriveId);
		$this->prepareGetUserUsername();
		$this->prepareGetPatronCirculation($patronCirculation);
	
		$actual = $this->service->isCheckedOutByPatron($this->userMock);
		$this->assertTrue($actual);
	}
	
	/**
	* method checkOut 
	* when called
	* should executesCorrectly
	*/
	public function test_checkOut_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$formatId = "aDummyFormatId";
		$this->prepareGetOverDriveID();
		$this->prepareGetUserUsername();
		$this->odAPIServicesMock->expects($this->once())
								->method("checkOut")
								->with($this->equalTo(self::username), $this->equalTo(self::overDriveIdCapitalLetters), $this->equalTo($formatId))
								->will($this->returnValue($expected));
		
		$actual = $this->service->checkOut($this->userMock, $formatId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method chooseFormat
	 * when called
	 * should executesCorrectly
	 */
	public function test_chooseFormat_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$formatId = "aDummyFormatId";
		$this->prepareGetOverDriveID();
		$this->prepareGetUserUsername();
		$this->odAPIServicesMock->expects($this->once())
								->method("chooseFormat")
								->with($this->equalTo(self::username), $this->equalTo(self::overDriveIdCapitalLetters), $this->equalTo($formatId))
								->will($this->returnValue($expected));
	
		$actual = $this->service->chooseFormat($this->userMock, $formatId);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method checkin 
	* when called
	* should returnAlwaysTrue
	*/
	public function test_checkin_called_returnAlwaysTrue()
	{
		$actual = $this->service->checkin($this->userMock);
		$this->assertTrue($actual);
	}
	
	/**
	 * method placeHold
	 * when called
	 * should executesCorrectly
	 */
	public function test_placeHold_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$email = "aDummyEmail";
		$this->prepareGetUserUsername();
		$this->prepareGetOverDriveID();
		$this->userMock->expects($this->once())
						->method("getEmail")
						->will($this->returnValue($email));
			
		$this->odAPIServicesMock->expects($this->once())
								->method("placeHold")
								->with($this->equalTo(self::username), $this->equalTo(self::overDriveIdCapitalLetters), $this->equalTo($email))
								->will($this->returnValue($expected));
	
		$actual = $this->service->placeHold($this->userMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method cancelHold
	 * when called
	 * should executesCorrectly
	 */
	public function test_cancelHold_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$formatId = "aDummyFormatId";
		$email = "aDummyEmail";
		$this->prepareGetUserUsername();
		$this->prepareGetOverDriveID();
	
		$this->odAPIServicesMock->expects($this->once())
								->method("cancelHold")
								->with($this->equalTo(self::username), $this->equalTo(self::overDriveIdCapitalLetters))
								->will($this->returnValue($expected));
	
		$actual = $this->service->cancelHold($this->userMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method canBeCheckIn 
	* when called
	* should returnAlwaysFalse
	*/
	public function test_canBeCheckIn_called_returnAlwaysFalse()
	{
		$actual = $this->service->canBeCheckIn();
		$this->assertFalse($actual);
	}
	
	/**
	* method getNumItems 
	* when called
	* should exeutesCorrectly
	*/
	public function test_getNumItems_called_exeutesCorrectly()
	{
		$expected = 2;
		$result = $this->overDriveMother->getItemMetadataResult();
		
		$this->prepareGetOverDriveID();
		$this->prepareGetItemMetadata($result);
		
		$actual = $this->service->getNumItems();
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getFormatType
	 * when formatIndexDoesNotExists
	 * should executesCorrectly
	 */
	public function test_getFormatType_formatIndexDoesNotExists_executesCorrectly()
	{
		$expected = "";
		$result = $this->overDriveMother->getItemMetadataResult();
	
		$this->prepareGetOverDriveID();
		$this->prepareGetItemMetadata($result);
	
		$actual = $this->service->getFormatType(123);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getFormatType 
	* when called
	* should executesCorrectly
	*/
	public function test_getFormatType_called_executesCorrectly()
	{
		$expected = "OverDrive WMA Audiobook";
		$result = $this->overDriveMother->getItemMetadataResult();
		
		$this->prepareGetOverDriveID();
		$this->prepareGetItemMetadata($result);
		
		
		$actual = $this->service->getFormatType();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getFormatType
	 * when getSecondItem
	 * should executesCorrectly
	 */
	public function test_getFormatType_getSecondItem_executesCorrectly()
	{
		$expected = "Ebook";
		$result = $this->overDriveMother->getItemMetadataResult();
	
		$this->prepareGetOverDriveID();
		$this->prepareGetItemMetadata($result);
	
		$actual = $this->service->getFormatType(2);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getFormats
	* when called
	* should executesCorrectly
	*/
	public function test_getFormats_called_executesCorrectly()
	{
		$expected = "OverDrive WMA Audiobook, Ebook";
		$result = $this->overDriveMother->getItemMetadataResult();
		
		$this->prepareGetOverDriveID();
		$this->prepareGetItemMetadata($result);
		
		$actual = $this->service->getFormats();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getFormatsInfo
	 * when called
	 * should executesCorrectly
	 */
	public function test_getFormatsInfo_called_executesCorrectly()
	{
		$expected[] = array("id"=>25, "name"=>"OverDrive WMA Audiobook");
		$expected[] = array("id"=>50, "name"=>"Ebook");
		$result = $this->overDriveMother->getItemMetadataResult();
	
		$this->prepareGetOverDriveID();
		$this->prepareGetItemMetadata($result);
	
		$actual = $this->service->getFormatsInfo();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getMsgAvailable 
	* when called
	* should returnAlwaysSameString
	*/
	public function test_getMsgAvailable_called_returnAlwaysSameString()
	{
		$expected = "Available from OverDrive";
		$actual = $this->service->getMsgAvailable();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMsgCheckedOut
	 * when called
	 * should returnAlwaysSameString
	 */
	public function test_getMsgCheckedOut_called_returnAlwaysSameString()
	{
		$expected = "Checked out in OverDrive";
		$actual = $this->service->getMsgCheckedOut();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMsgCheckedOutToYou
	 * when called
	 * should returnAlwaysFalse
	 */
	public function test_getMsgCheckedOutToYou_called_returnAlwaysSameString()
	{
		$actual = $this->service->getMsgCheckedOutToYou();
		$this->assertFalse($actual);
	}
	
	/**
	 * method getUsageNotesMessage
	 * when called
	 * should returnAlwaysFalse
	 */
	public function test_getUsageNotesMessage_called_returnAlwaysSameString()
	{
		$actual = $this->service->getUsageNotesMessage();
		$this->assertFalse($actual);
	}
	
	/**
	* method getAccessUrls 
	* when called
	* should executesCorrectly
	* @dataProvider DP_getAccessUrls
	*/
	public function test_getAccessUrls_called_executesCorrectly($overDriveId)
	{
		$expected = "aDummyLink-EA87339B";
		$patronCirculation = $this->overDriveMother->getPatronCirculation();
		$this->prepareGetUserUsername();
		$this->prepareGetOverDriveID($overDriveId);
		$this->prepareGetPatronCirculation($patronCirculation);
		
		$actual = $this->service->getAccessUrls($this->userMock);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getAccessUrls()
	{
		return array(
						array("EA87339B-9B92-423E-B413-D9A17BF33AF9"),
						array("ea87339b-9b92-423e-b413-d9a17bf33Af9")
					);
	}
	
	/**
	* method getAccessUrls 
	* when itemCheckedOutNotChooseFormat
	* should executesCorrectly
	*/
	public function test_getAccessUrls_itemCheckedOutNotChooseFormat_executesCorrectly()
	{
		$idEcontentRecord = "aDummyId";
		$expected = "/EcontentRecord/".$idEcontentRecord."/CFormat";
		
		$this->econtentRecordMock->id = $idEcontentRecord;
		$patronCirculation = $this->overDriveMother->getPatronCirculation();
		$this->prepareGetUserUsername();
		$this->prepareGetOverDriveID("833283EE-3A23-45FB-B5DF-217DEC6C2D02");
		$this->prepareGetPatronCirculation($patronCirculation);
				
		$actual = $this->service->getAccessUrls($this->userMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getMethodLoadStatusSummaries 
	* when called
	* should returnUnique
	*/
	public function test_getMethodLoadStatusSummaries_called_returnUnique()
	{
		$expected = EcontentRecordConstants::MethodUniqueToLoadStatusSummaries;
		$actual = $this->service->getMethodLoadStatusSummaries();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getSize 
	* when called
	* should returnCorrectly
	* @dataProvider DP_getSize
	*/
	public function test_getSize_called_returnCorrectly($itemIndex, $expected)
	{
		$result = $this->overDriveMother->getItemMetadataResult();
		
		$this->prepareGetOverDriveID();
		$this->prepareGetItemMetadata($result);
		
		$actual = $this->service->getSize($itemIndex);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getSize()
	{
		return array(
						array(1, "320"),
						array(2, "0")
					);		
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
	* method showCancelHoldLinkAvailableHolds 
	* when called
	* should returnAlwaysTrue
	*/
	public function test_showCancelHoldLinkAvailableHolds_called_returnAlwaysTrue()
	{
		$actual = $this->service->showCancelHoldLinkAvailableHolds();
		$this->assertTrue($actual);
	}
	
	/**
	 * method canSuspendHolds
	 * when called
	 * should returnAlwaysFalse
	 */
	public function test_canSuspendHolds_called_returnAlwaysFalse()
	{
		$actual = $this->service->canSuspendHolds();
		$this->assertFalse($actual);
	}
	
	/**
	* method removeWishList 
	* when called
	* should executesCorrectly
	*/
	public function test_removeWishList_called_executesCorrectly()
	{
		$this->prepareGetUserUsername();
		$this->prepareGetOverDriveID();
		
		$this->odAPIServicesMock->expects($this->once())
								->method("removeWishList")
								->with($this->equalTo(self::username), $this->equalTo(self::overDriveIdCapitalLetters));
		
		$actual = $this->service->removeWishList($this->userMock);
		$this->assertTrue($actual);
		
	}
	
	/**
	 * method addWishList
	 * when called
	 * should executesCorrectly
	 */
	public function test_addWishList_called_executesCorrectly()
	{
		$this->prepareGetUserUsername();
		$this->prepareGetOverDriveID();
	
		$this->odAPIServicesMock->expects($this->once())
								->method("addToWishList")
								->with($this->equalTo(self::username), $this->equalTo(self::overDriveIdCapitalLetters));
	
		$actual = $this->service->addWishList($this->userMock);
		$this->assertTrue($actual);
	
	}
	
		
		
	//PRIVATES
	private function prepareGetItemMetadata($result)
	{
		$this->odAPIServicesMock->expects($this->once())
								->method("getItemMetadata")
								->with($this->equalTo(self::overDriveIdCapitalLetters))
								->will($this->returnValue($result));
	}
	
	private function prepareGetPatronCirculation($result)
	{
		$this->odAPIServicesMock->expects($this->once())
								->method("getPatronCirculation")
								->with($this->equalTo(self::username))
								->will($this->returnValue($result));
	}
	
	private function prepareGetItemDetails($result)
	{
		$this->odAPIServicesMock->expects($this->once())
								->method("getItemDetails")
								->with($this->equalTo(self::overDriveIdCapitalLetters))
								->will($this->returnValue($result));
	}
	
	private function prepareGetOverDriveID($overDriveId = NULL)
	{
		if(!$overDriveId) $overDriveId = self::overDriveId;
		
		$this->econtentRecordMock->expects($this->once())
									->method("getSourceUrl")
									->will($this->returnValue(self::sourceUrlValue));
		
		$this->regExpMock->expects($this->once())
						 ->method("getFieldValueFromURL")
						 ->with($this->equalTo(self::sourceUrlValue), $this->equalTo("ID"))
						 ->will($this->returnValue($overDriveId));
	}
	
	private function prepareGetUserUsername()
	{
		$this->userMock->expects($this->once())
						->method("getUsername")
						->will($this->returnValue(self::username));
	}
	
	private function prepareCallGetItemDetailsCache($result)
	{
		$this->odAPIServicesMock->expects($this->once())
								->method("getItemDetails")
								->with($this->equalTo(self::overDriveIdCapitalLetters))
								->will($this->returnValue($result));
	}
	
}
?>