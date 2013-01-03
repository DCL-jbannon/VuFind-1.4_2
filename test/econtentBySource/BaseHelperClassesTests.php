<?php

abstract class BaseHelperClassesTests extends PHPUnit_Framework_TestCase
{
	protected $service;
	protected $econtentRecordDetailsMock;
	
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
															  "checkout","checkin", "placeHold", "cancelHold",
															  "getAccessUrls","getRecordId", "showAddItemButton",
															  "getCancelHoldUrls", "getSize", "getUsageNotesMessage", "getCheckOutUrls"));
		parent::setUp();
	}
	
}
?>