<?php
require_once dirname(__FILE__).'/../../web/services/MyResearch/lib/User.php';

interface IEcontentRecordDetails
{
	
	//Actions Availability
	
	/**
	 * Returns true if the record is available for check out
	 * @return boolean
	 */
	public function isCheckOutAvailable();
	
	/**
	 * Returns true if the record is available for Place Hold
	 * @return boolean
	 */
	public function isPlaceHoldAvailable();
	
	/**
	 * Returns true if the record is available for Add to Wish List
	 * @return boolean
	 */
	public function isAddWishListAvailable();
	
	/**
	 * Returns true if the record is available for Access Online
	 * @return boolean
	 */
	public function isAccessOnlineAvailable();
	
	//Statistics Methods
	
	/**
	 * Returns true if this kind of econtent exists the CheckIn option when the title is checked out
	 * @return boolean
	 */
	public function canBeCheckIn();
	
	/**
	 * Return the Total Number of Copies of the Record
	 * @return integer
	 */
	public function getTotalCopies();
	
	/**
	 * Return the Number of Available Copies for the Record
	 * @return integer
	 */
	public function getAvailableCopies();
	
	/**
	 * Return the lenght of the Hold Queue.
	 * @return integer
	 */
	public function getHoldLength();
	
	/**
	 * Return the lenght of the Wish List
	 * @return integer
	 */
	public function getWishListSize();
		
	//Actions By Patrons
	/**
	 * Returns true if the record is available for Cancel Hold for the patron
	 * @param integer patronId
	 * @return boolean
	 */
	public function isCancelHoldAvailable(IUser $user);
	/**
	 * Returns true if the record it has been checked out for the patron
	 * @param integer patronId
	 * @return boolean
	 */
	public function isCheckedOutByPatron(IUser $user);
	
	//Actions over Items
	/**
	 * Return false if the check out process fails
	 * @param integer $patronId
	 * @return boolean|void
	 */
	public function checkout(IUser $user);
	
	/**
	 * Return false if the check in process fails
	 * @param integer $patronId
	 * @return boolean|void
	 */
	public function checkin(IUser $user);
	/**
	 * Return false if the check in process fails
	 * @param integer $patronId
	 * @return boolean|void
	 */
	public function placeHold(IUser $user);
	
	/**
	 * Return false if the cancel hold process fails
	 * @param integer $patronId
	 * @return boolean
	 */
	public function cancelHold(IUser $user);
	
	/**
	 * Remove the item from the WishList
	 */
	public function removeWishList(IUser $user);
	
	/**
	 * Add the item to a WishList
	 */
	public function AddWishList(IUser $user);
	
	//Formats
	/**
	 * Return the number of items associated to this econtent
	 */
	public function getNumItems();
	
	/**
	 * Return the format type for the item requested asociated by this econtent record
	 * @param integer itemIndex
	 */
	public function getFormatType($itemIndex = 1);
	
	/**
	 * Return a string list comma separated of the formats availables for this econtent (epub,pdf,video, .....)
	 */
	public function getFormats();
	
	//Custom Status Messages
	//
	/**
	 * When return false means that the class EcontentRecordStatusText will the generic messages
	 * @return boolean|string
	 */
	public function getMsgAvailable();
	
	/**
	 * When return false means that the class EcontentRecordStatusText will the generic messages
	 * @return boolean|string
	 */
	public function getMsgCheckedOut();
	
	/**
	 * When return false means that the class EcontentRecordStatusText will the generic messages
	 * @return boolean|string
	 */
	public function getMsgCheckedOutToYou();
	
	/**
	 * When return false means that the class EcontentRecordStatusText will the generic messages
	 * @return boolean|string
	 */
	public function getUsageNotesMessage();
	
	/**
	 * Return all the access urls that this econtent type has
	 * @param integer $patronId
	 */
	public function getAccessUrls(IUser $user);
	
	//Generic Values
	/**
	 * Define how to load the Status Summaries of this record via AJAX when Searching
	 * Returns: 
	 * 			EcontentRecordConstants::MethodUniqueToLoadStatusSummaries
	 *		    EcontentRecordConstants::MethodMultipleToLoadStatusSummaries
	 */
	public function getMethodLoadStatusSummaries();
	
	/**
	 * Get the Size of the Record
	 * @return integer
	 */
	public function getSize($itemIndex = 1);
	
	/**
	 * Return if on the EcontentRecord Detail Page has to show the button Add Item button
	 * @return boolean
	 */
	public function showAddItemButton();
	
	/**
	 * Return if on the Available Holds Page has to show the button Cancel Hold for this EcontentRecord type
	 * @return boolean
	 */
	public function showCancelHoldLinkAvailableHolds();
	
	/**
	 * Return if the record can be suspended when the patron has placed on hold the item.
	 * IT NOT THE SAME AS CANCEL HOLD!!!
	 * @return boolean
	 */
	public function canSuspendHolds();
}
?>