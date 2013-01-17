<?php
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
		public function isCancelHoldAvailable($patronId);
		/**
		 * Returns true if the record it has been checked out for the patron
		 * @param integer patronId
		 * @return boolean
		 */
		public function isCheckedOutByPatron($patronId);
		
		//Actions over Items
		/**
		 * Return false if the check out process fails
		 * @param integer $patronId
		 * @return boolean|void
		 */
		public function checkout($patronId);
		
		/**
		 * Return false if the check in process fails
		 * @param integer $patronId
		 * @return boolean|void
		 */
		public function checkin($patronId);
		/**
		 * Return false if the check in process fails
		 * @param integer $patronId
		 * @return boolean|void
		 */
		public function placeHold($patronId);
		
		/**
		 * Return false if the cancel hold process fails
		 * @param integer $patronId
		 * @return boolean
		 */
		public function cancelHold($patronId);
		
		//Formats
		public function getFormatType();
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
		public function getAccessUrls($patronId  = NULL);
		
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
		public function getSize();
		
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
		 * Return if the record can be suspended when the patron has placed on hold the item
		 * @return boolean
		 */
		public function canSuspendHolds();
	}
?>