<?php

abstract class BaseHelperClassesTests extends PHPUnit_Framework_TestCase
{
	protected $service;
	protected $econtentRecordDetailsMock;
	protected $userMock;
	
	public function setUp()
	{
		$this->econtentRecordDetailsMock = $this->getMock("IEcontentRecordDetails", 
														array("isCheckOutAvailable", "isCheckedOutByPatron",
															  "isPlaceHoldAvailable", "isAddWishListAvailable",
															  "isCancelHoldAvailable", "isAccessOnlineAvailable",
															  "getTotalCopies", "getAvailableCopies",
															  "getHoldLength", "getWishListSize",
															  "getFormatType", "getMsgAvailable",
															  "getMsgCheckedOut", "getMsgCheckedOutToYou",
															  "getMethodLoadStatusSummaries", "getFormats",
															  "checkout","checkin", "placeHold", "cancelHold","canSuspendHolds",
															  "getAccessUrls","getRecordId", "showAddItemButton", "showCancelHoldLinkAvailableHolds",
															  "getCancelHoldUrls", "getSize", "getUsageNotesMessage", "getCheckOutUrls", 
															  "canBeCheckIn", "getNumItems", "removeWishList", "AddWishList"));
		$this->userMock = $this->getMock("IUser");	
		parent::setUp();
	}
	
}
?>