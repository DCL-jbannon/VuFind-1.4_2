<?php
require_once 'Interface.php';
require_once 'sys/eContent/EContentRecord.php';
require_once 'sys/eContent/EContentItem.php';
require_once 'sys/eContent/EContentHold.php';
require_once 'sys/eContent/EContentCheckout.php';
require_once 'sys/eContent/EContentWishList.php';
require_once dirname(__FILE__).'/../../classes/Utils/ArrayUtils.php';
require_once dirname(__FILE__).'/../../classes/services/IDCLReaderServices.php';
require_once dirname(__FILE__).'/../../classes/services/OverDriveServices.php';
require_once dirname(__FILE__).'/../../classes/services/FreeEContentRecordServices.php';
require_once dirname(__FILE__).'/../../classes/API/OverDrive/OverDriveServicesAPI.php';
require_once dirname(__FILE__).'/../../classes/econtentBySource/EcontentDetailsFactory.php';
require_once dirname(__FILE__).'/../../classes/econtentBySource/EcontentRecordStatusText.php';
require_once dirname(__FILE__).'/../../classes/econtentBySource/EcontentRecordShowButtons.php';
require_once dirname(__FILE__).'/../../classes/API/3M/ThreeMAPI.php';
require_once dirname(__FILE__).'/../../classes/Utils/ThreeMUtils.php';
require_once dirname(__FILE__).'/../../classes/Utils/OverDriveUtils.php';

class EContentDriver implements DriverInterface
{
	private static $holdings = array();
	
	/**
	 * Get Status
	 *
	 * This is responsible for retrieving the status information of a certain
	 * record.
	 *
	 * @param   string  $recordId   The record id to retrieve the holdings for
	 * @return  mixed               An associative array with the following keys:
	 *                              availability (boolean), status, location,
	 *                              reserve, callnumber
	 *                              If an error occures, return a PEAR_Error
	 * @access  public
	 */
	public function getStatus($id){

	}

	/**
	 * Get Statuses
	 *
	 * This is responsible for retrieving the status information for a
	 * collection of records.
	 *
	 * @param   array  $recordIds   The array of record ids to retrieve the
	 *                              status for
	 * @return  mixed               An associative array with the following keys:
	 *                              availability (boolean), status, location,
	 *                              reserve, callnumber
	 *                              If an error occures, return a PEAR_Error
	 * @access  public
	 */
	public function getStatuses($ids){

	}

	/**
	 * Get Holding
	 *
	 * This is responsible for retrieving the holding information of a certain
	 * record.
	 *
	 * @param   string  $recordId   The record id to retrieve the holdings for
	 * @return  mixed               An associative array with the following keys:
	 *                              availability (boolean), status, location,
	 *                              reserve, callnumber, duedate, number,
	 *                              holding summary, holding notes
	 *                              If an error occurs, return a PEAR_Error
	 * @access  public
	 */
	public function getHolding($id)
	{
		if (array_key_exists($id, EContentDriver::$holdings)){
			return EContentDriver::$holdings[$id];
		}
		
		/** $user User */
		global $user, $configArray;
		$libaryScopeId = $this->getLibraryScopingId();
		//Get any items that are stored for the record
		$eContentRecord = new EContentRecord();
		$eContentRecord->id = $id;
		$eContentRecord->find(true);
		
		
		$checkedOut = false;
		$onHold = false;
		$holdPosition = 0;
		
		$detailsEcontent = EcontentDetailsFactory::get($eContentRecord);
		if($detailsEcontent !== false)
		{
			if($user)
			{
				$patronId = $user->getBarcode();
				$checkedOut = $detailsEcontent->isCheckedOutByPatron($user);
				$onHold = $detailsEcontent->isCancelHoldAvailable($user);
			}
			
			$item->item_type = $detailsEcontent->getFormatType();
			$item->source = $detailsEcontent->getSourceName();
			$item->usageNotes = $detailsEcontent->getStatusText()->getUsageNotesMessage();
			$item->size = $detailsEcontent->getSize();
			$item->checkedOut = $checkedOut;
			$item->onHold = $onHold;
			$item->holdPosition = $holdPosition;
			$item->notes = '';
			$item->showActionButtons = false;
			$items[] = $item;
			
		}
		else
		{
			if ($user)
			{
				$eContentCheckout = new EContentCheckout();
				$eContentCheckout->userId = $user->id;
				$eContentCheckout->status = 'out';
				$eContentCheckout->recordId = $id;
				$eContentCheckout->find();
				if ($eContentCheckout->N > 0)
				{
					//The item is checked out to the current user
					$eContentCheckout->fetch();
					$checkedOut = true;
				}
				else
				{
					$eContentHold = new EContentHold();
					$eContentHold->userId = $user->id;
					$eContentHold->whereAdd("status in ('active', 'suspended', 'available')");
					$eContentHold->recordId = $id;
					$eContentHold->find();
					if ($eContentHold->N > 0)
					{
						$onHold = true;
						$eContentHold->fetch();
						$holdPosition = $this->_getHoldPosition($eContentHold);
					}
				}
			}
			
			$eContentItem = new EContentItem();
			$eContentItem->recordId = $id;
			if ($libaryScopeId != -1)
			{
				$eContentItem->whereAdd("libraryId = -1 or libraryId = $libaryScopeId");
			}
			$items = array();
			$eContentItem->find();
			while ($eContentItem->fetch())
			{
				$item = clone $eContentItem;
				$item->source = $eContentRecord->source;
				//Generate links for the items
				$links = array();
				if ($checkedOut)
				{
					$links = $this->_getCheckedOutEContentLinks($eContentRecord, $item, $eContentCheckout);
				}
				else if ($eContentRecord->isFree() && $item->isExternalItem())
				{
					$links = $this->_getFreeExternalLinks($eContentRecord, $item);
				}
				else if ($onHold)
				{
					$links = $this->getOnHoldEContentLinks($eContentHold);
				}
				
				$item->showActionButtons = true;
				$item->checkedOut = $checkedOut;
				$item->onHold = $onHold;
				$item->holdPosition = $holdPosition;
				$item->links = $links;
				$items[] = $item;
			}
		}
		EContentDriver::$holdings[$id] = $items;
		return $items;
	}
	
	public function getLibraryScopingId(){
		$searchLibrary = Library::getSearchLibrary();
		$searchLocation = Location::getSearchLocation();

		//Load the holding label for the branch where the user is physically.
		if (!is_null($searchLocation)){
			return $searchLocation->libraryId;
		}else if (isset($searchLibrary)) {
			return $searchLibrary->libraryId;
		}else{
			return -1;
		}
	}
	
public function getStatusSummary($id, $holdings)
{ 
	/** $user User */
	global $user, $configArray;
	
	//Get the eContent Record
	$eContentRecord = new EContentRecord();
	$eContentRecord->id = $id;
	$eContentRecord->find(true);

	$drmType = $eContentRecord->accessType;
	$checkedOut = false;
	$onHold = false;
	$addedToWishList = false;
	$holdPosition = 0;
	
			//Load status summary
	$statusSummary = array();
	$statusSummary['recordId'] = $id;
	$statusSummary['totalCopies'] = $eContentRecord->availableCopies;
	$statusSummary['onOrderCopies'] = $eContentRecord->onOrderCopies;
	$statusSummary['accessType'] = $eContentRecord->accessType;
	foreach ($holdings as $item)
	{
		$checkedOut = $item->checkedOut;
		$onHold = $item->onHold;
		$holdPosition = $item->holdPosition;
	}
	
	$details = EcontentDetailsFactory::get($eContentRecord);
	$overdriveTitle = false;
	if($details !== false)
	{	
		if(!$user) $user = NULL;
		$statusSummary['totalCopies'] = $details->getTotalCopies();
		$statusSummary['availableCopies'] = $details->getAvailableCopies();
		$statusSummary['numHolds'] = $details->getHoldLength();
		$statusSummary['holdQueueLength'] = $details->getHoldLength();
		
		$statusSummary['status'] = $details->getStatusText()->getString($user);
		$wishListSize = $details->getWishListSize();
		
		//Buttons
		$statusSummary['showPlaceHold'] = $details->getShowButtons()->showPlaceHold($user);
		$statusSummary['showCheckout'] =  $details->getShowButtons()->showCheckOut($user);
		$statusSummary['showAddToWishlist'] = $details->getShowButtons()->showAddToWishList($user);
		$statusSummary['showAccessOnline'] = $details->getShowButtons()->showAccessOnline();
		$statusSummary['formatType'] = $details->getFormatType();
	}
	else
	{
		//Check to see if it is checked out
		$checkouts = new EContentCheckout();
		$checkouts->status = 'out';
		$checkouts->recordId = $id;
		$checkouts->find();
		$statusSummary['numCheckedOut'] = $checkouts->N;

		//Get a count of the holds on the record
		$holds = new EContentHold();
		$holds->recordId = $id;
		$holds->whereAdd("(status = 'active' or status = 'suspended')");
		$holds->find();
		$statusSummary['numHolds'] = $holds->N;

		//Get a count of the available holds on the record
		$holds = new EContentHold();
		$holds->recordId = $id;
		$holds->status = 'available';
		$holds->find();
		$statusSummary['numAvailableHolds'] = $holds->N;
		
		//Check to see if the record is on the user's wishlist
		if ($user)
		{
			$eContentWishList = new EContentWishList();
			$eContentWishList->userId = $user->id;
			$eContentWishList->recordId = $id;
			$eContentWishList->status = 'active';
			$eContentWishList->find();
			if ($eContentWishList->N > 0){
				$addedToWishList = true;
			}
		}
		
		if (count($holdings) == 0)
		{
			$statusSummary['availableCopies'] = 0; 
		}
		else
		{
			$statusSummary['availableCopies'] = $statusSummary['totalCopies'] - $statusSummary['numCheckedOut'] - $statusSummary['numAvailableHolds'];
		}

		if ($checkedOut == true)
		{
			$statusSummary['status'] = 'Checked Out to you';
			$statusSummary['available'] = false;
		}
		elseif ($onHold == true)
		{
			$statusSummary['status'] = 'On Hold for you';
			$statusSummary['available'] = false;
		}
		elseif ($addedToWishList == true)
		{
			$statusSummary['status'] = 'On your wishlist';
			$statusSummary['available'] = false;
		}
		elseif (count($holdings) == 0)
		{
			$statusSummary['status'] = 'Not available yet';
			$statusSummary['available'] = false;
		}
		elseif ($statusSummary['numCheckedOut'] < $statusSummary['totalCopies'])
		{
			$statusSummary['status'] = 'Available Online';
			$statusSummary['available'] = true;
		}
		else
		{
			$statusSummary['status'] = 'Checked Out';
			$statusSummary['available'] = false;
		}

		$wishList = new EContentWishList();
		$wishList->recordId = $id;
		$wishList->status = 'active';
		$wishList->find();
		$wishListSize = $wishList->N;
	}
		
	//Determine which buttons to show
	$statusSummary['source'] = $eContentRecord->source;
	
	$isFreeExternalLink = $eContentRecord->isFree();

	if($details !== false)
	{
		//Done it before
	}
	elseif ($isFreeExternalLink)
	{
		$freeEContentRecordService = new FreeEcontentRecordServices();
		
		$statusSummary['showPlaceHold'] = false;
		$statusSummary['showCheckout'] = false;
		$statusSummary['showAddToWishlist'] = false;
		
		$statusSummary['showAccessOnline'] = true;
		$statusSummary['formatType'] = $freeEContentRecordService->getFormatType($eContentRecord);
		$statusSummary['holdQueueLength'] = 0;
	}
	else
	{
		$statusSummary['showPlaceHold'] = (!$checkedOut && !$onHold) && $drmType != 'free' && ($statusSummary['availableCopies'] == 0) && count($holdings) > 0;
		$statusSummary['showCheckout'] = (!$checkedOut && !$onHold) && ($statusSummary['availableCopies'] > 0);
		$statusSummary['showAddToWishlist'] = (count($holdings) == 0 && !$addedToWishList);
		
		$statusSummary['showAccessOnline'] = ($checkedOut && count($holdings) > 0);
		$statusSummary['formatType'] = '';
		$statusSummary['holdQueueLength'] = $this->getWaitList($id);
	}
	
	
	$statusSummary['onHold'] = $onHold;
	$statusSummary['checkedOut'] = $checkedOut;
	$statusSummary['holdPosition'] = $holdPosition;
	$statusSummary['numHoldings'] = count($holdings);
	$statusSummary['wishListSize'] = $wishListSize;

	return $statusSummary;
}

public function getStatusSummaries($ids){
	$summaries = array();
	if (is_array($ids) && count($ids) > 0){
		foreach ($ids as $id){
			$holdings = $this->getHolding($id);
			//Load status summary
			$result = $this->getStatusSummary($id, $holdings);
			if (PEAR::isError($result)) {
				PEAR::raiseError($result);
			}
			$summaries[$id] = $result;
		}
	}
	return $summaries;
}

	public function getPurchaseHistory($id){
	
	}

	public function getMyHolds($user){
		
		$holds = array();
				
		$holds['holds']['available'] = $this->getAvailableHolds($user);
		$holds['holds']['unavailable'] = $this->getUnAvailableHolds($user);
		
		return $holds;
	}
	
	
	private function getAvailableHolds($user)
	{
		global $configArray;
		
		$holds = array();
		$availableHolds = new EContentHold();
		$availableHolds->userId = $user->id;
		$availableHolds->status ='available';
		$availableHolds->find();
		while ($availableHolds->fetch()){
			$eContentRecord = new EContentRecord();
			$eContentRecord->id = $availableHolds->recordId;
			if ($eContentRecord->find(true)){
				$expirationDate = $availableHolds->dateUpdated + 5 * 24 * 60 * 60;
				$holds[] = array(
						'id' => $eContentRecord->id,
						'recordId' => 'econtentRecord' . $eContentRecord->id,
						'source' => $eContentRecord->source,
						'title' => $eContentRecord->title,
						'author' => $eContentRecord->author,
						'available' => true,
						'create' => $availableHolds->datePlaced,
						'expire' => $expirationDate,
						'status' => $availableHolds->status,
						'links' => $this->getOnHoldEContentLinks($availableHolds)
				);
			}
		}
		
		//3M
		$threeMAPI = new ThreeMAPI();
		$patronCirculation = $threeMAPI->getPatronCirculation($user->getBarcode());
		if(count($patronCirculation->Reserves[0]) > 0)
		{
			
			foreach($patronCirculation->Reserves[0] as $reserve)
			{
				$eContentRecord = ThreeMUtils::getEcontentRecordFrom3MId($reserve->ItemId);
				$details = EcontentDetailsFactory::get($eContentRecord);
				
				$holds[] = array(
						'id' => $eContentRecord->id,
						'recordId' => 'econtentRecord' . $eContentRecord->id,
						'source' => $eContentRecord->getSource(),
						'title' => $eContentRecord->getTitle(),
						'author' => $eContentRecord->getAuthor(),
						'available' => true,
						'create' => $reserve->EventStartDateInUTC,
						'expire' => $reserve->EventEndDateInUTC,
						'status' => '',
						'links' => $details->getLinksInfo()->getLinksAvailableHolds()
				);
				
			}
		}
		
	//OverDrive
		$overDriveAPIServ = new OverDriveServicesAPI();
		$results = $overDriveAPIServ->getPatronCirculation($user->getBarcode());
		
		if ($results !== false)
		{
			$itemsId = array();
			foreach ($results->AvailableHolds as $item)
			{
				$itemsId[] = $item['ItemId'];
			}
			$overDriveAPIServ->getMultipleItemsDetail($itemsId, $user->getBarcode());
			
			foreach ($results->AvailableHolds as $item)
			{
				$id = $item['ItemId'];
				$eContentRecord = OverDriveUtils::getEcontentRecordFromOverDriveID($id);
				$details = EcontentDetailsFactory::get($eContentRecord);
		
				$holds[] = array(
						'id' => $eContentRecord->id,
						'recordId' => 'econtentRecord' . $eContentRecord->id,
						'source' => $eContentRecord->getSource(),
						'title' => $eContentRecord->getTitle(),
						'author' => $eContentRecord->getAuthor(),
						'available' => true,
						'create' => '',
						'expire' => 'active',
						'status' => '',
						'links' => $details->getLinksInfo()->getLinksAvailableHolds()
				);
		
			}
		}
		
		return $holds;
	}
	
	
	private function  getUnAvailableHolds($user)
	{
		global $configArray;
		
		$holds = array();
		$unavailableHolds = new EContentHold();
		$unavailableHolds->userId = $user->id;
		$unavailableHolds->whereAdd("(status = 'active' or status = 'suspended')");
		$unavailableHolds->find();
		while ($unavailableHolds->fetch()){
			$eContentRecord = new EContentRecord();
			$eContentRecord->id = $unavailableHolds->recordId;
			if ($eContentRecord->find(true)){
				$holds[] = array(
						'id' => $eContentRecord->id,
						'recordId' => 'econtentRecord' . $eContentRecord->id,
						'source' => $eContentRecord->source,
						'title' => $eContentRecord->title,
						'author' => $eContentRecord->author,
						'available' => true,
						'createTime' => $unavailableHolds->datePlaced,
						'status' => $unavailableHolds->status,
						'position' => $this->_getHoldPosition($unavailableHolds),
						'links' => $this->getOnHoldEContentLinks($unavailableHolds),
						'frozen' => $unavailableHolds->status == 'suspended',
						'reactivateDate' => $unavailableHolds->reactivateDate,
						'canSuspendHolds' => true
				);
			}
		}
		
		//3M
		$threeMAPI = new ThreeMAPI();
		$patronCirculation = $threeMAPI->getPatronCirculation($user->getBarcode());
		if(count($patronCirculation->Holds[0]) > 0)
		{
			foreach($patronCirculation->Holds[0] as $hold)
			{
				$eContentRecord = ThreeMUtils::getEcontentRecordFrom3MId($hold->ItemId);
				$details = EcontentDetailsFactory::get($eContentRecord);
				
				$holds[] = array(
						'id' => $eContentRecord->id,
						'recordId' => 'econtentRecord' . $eContentRecord->id,
						'source' => $eContentRecord->source,
						'title' => $eContentRecord->title,
						'author' => $eContentRecord->author,
						'available' => true,
						'createTime' => $hold->EventStartDateInUTC,
						'status' => 'active',
						'position' => 'unknown',
						'links' => $details->getLinksInfo()->getCancelHoldsLinks(),
						'frozen' => false,
						'reactivateDate' => '',
						'canSuspendHolds' => $details->canSuspendHolds()
				);
			}
		}
		
		//OverDrive
		$overDriveAPIServ = new OverDriveServicesAPI();		
		$results = $overDriveAPIServ->getPatronCirculation($user->getBarcode());
		
		if ($results !== false)
		{
			
			$itemsId = array();
			foreach ($results->Holds as $item)
			{
				$itemsId[] = $item['ItemId'];
			}
			$overDriveAPIServ->getMultipleItemsDetail($itemsId, $user->getBarcode());
			
			foreach ($results->Holds as $item)
			{
				$id = $item['ItemId'];
				$eContentRecord = OverDriveUtils::getEcontentRecordFromOverDriveID($id);
				$details = EcontentDetailsFactory::get($eContentRecord);
		
				$holds[] = array(
						'id' => $eContentRecord->id,
						'recordId' => 'econtentRecord' . $eContentRecord->id,
						'source' => $eContentRecord->source,
						'title' => $eContentRecord->title,
						'author' => $eContentRecord->author,
						'available' => true,
						'createTime' => '',
						'status' => 'active',
						'position' => $item['UserPosition']." of ".$item['QueueLength'],
						'links' => $details->getLinksInfo()->getCancelHoldsLinks(),
						'frozen' => false,
						'reactivateDate' => '',
						'canSuspendHolds' => $details->canSuspendHolds()
				);
		
			}
		}
		
		
		
		return $holds;
	}

	private function _getHoldPosition($existingHold){
		$eContentHold = new EContentHold();
		$eContentHold->recordId = $existingHold->recordId;
		$eContentHold->whereAdd("datePlaced < {$existingHold->datePlaced} AND (status = 'active')");
		$eContentHold->find();
		return $eContentHold->N + 1;
	}

	public function getMyTransactions($user)
	{
		global $configArray;
		
		$idclReaderService = new IDCLReaderServices();
		
		$isIdclReader = $configArray['iDCLReader']['isIDCLReader'];
		
		$return = array();
		$eContentCheckout = new EContentCheckout();
		$eContentCheckout->userId = $user->id;
		$eContentCheckout->status = 'out';
		$eContentCheckout->find();
		
		$return['transactions'] = array();
		$return['numTransactions'] = $eContentCheckout->find();
		while ($eContentCheckout->fetch())
		{
			$eContentRecord = new EContentRecord();
			$eContentRecord->id = $eContentCheckout->recordId;
			if ($eContentRecord->find(true)){
				$daysUntilDue = ceil(($eContentCheckout->dateDue - time()) / (24 * 60 * 60));
				$overdue = $daysUntilDue < 0;
				$waitList = $this->getWaitList($eContentRecord->id);
				$links = $this->_getCheckedOutEContentLinks($eContentRecord, null, $eContentCheckout);
				
				$validEContent = true;
				if ($isIdclReader)
				{
					$validEContent = $idclReaderService->isValidEContent($eContentRecord->id);
				}
				
				if($validEContent)
				{
					$return['transactions'][] = array(
						'id' => $eContentRecord->id,
						'recordId' => 'econtentRecord' . $eContentRecord->id,
						'source' => $eContentRecord->source,
						'title' => $eContentRecord->title,
						'author' => $eContentRecord->author,
						'duedate' => $eContentCheckout->dateDue,
						'checkoutdate' => $eContentCheckout->dateCheckedOut,
						'daysUntilDue' => $daysUntilDue,
						'holdQueueLength' => $waitList,
						'links' => $links,
					);
				}
			}
		}

		//3M
		$threeMAPI = new ThreeMAPI();
		$results = $threeMAPI->getPatronCirculation($user->getBarcode());
		if ($results !== false)
		{
			foreach ($results->Checkouts[0] as $item)
			{
				$id = $item->ItemId;
				$eContentRecord = ThreeMUtils::getEcontentRecordFrom3MId($id);
				
				if($eContentRecord !== false) //The item is not as part of our Marc Record Collection
				{
					$details = EcontentDetailsFactory::get($eContentRecord);
					$daysUntilDue = (strtotime($item->EventEndDateInUTC) - strtotime($item->EventStartDateInUTC)) / (24 * 60 * 60);
					
					$return['transactions'][] = array(
							'id' => $eContentRecord->id,
							'recordId' => 'econtentRecord' . $eContentRecord->id,
							'source' => $eContentRecord->source,
							'title' => $eContentRecord->title,
							'author' => $eContentRecord->author,
							'duedate' => $item->EventEndDateInUTC,
							'checkoutdate' => $item->EventStartDateInUTC,
							'daysUntilDue' => $daysUntilDue,
							'holdQueueLength' => $details->getHoldLength(),
							'links' => $details->getLinksInfo()->getLinksItemChekedOut($user, $details->canBeCheckIn())
					);
				}
			}
		}
		
		//OverDrive
		$username = $user->getUsername();
		$overDriveAPIServ = new OverDriveServicesAPI();
		$results = $overDriveAPIServ->getPatronCirculation($username);
		if ($results !== false)
		{
			$itemsId = array();
			foreach ($results->Checkouts as $item)
			{
				$itemsId[] = $item['ItemId'];
			}

			$overDriveAPIServ->getMultipleItemsDetail($itemsId, $username);
			foreach ($results->Checkouts as $item)
			{
				$id = $item['ItemId'];
				$eContentRecord = OverDriveUtils::getEcontentRecordFromOverDriveID($id);
				if($eContentRecord !== false)
				{
					$details = EcontentDetailsFactory::get($eContentRecord);
					$daysUntilDue = ceil((strtotime($item['Expires']) - mktime()) / (24 * 60 * 60));
					
					$return['transactions'][] = array(
							'id' => $eContentRecord->id,
							'recordId' => 'econtentRecord' . $eContentRecord->id,
							'source' => $eContentRecord->source,
							'title' => $eContentRecord->title,
							'author' => $eContentRecord->author,
							'duedate' => $item['Expires'],
							'checkoutdate' => '',
							'daysUntilDue' => $daysUntilDue,
							'holdQueueLength' => $details->getHoldLength(),
							'links' => $details->getLinksInfo()->getLinksItemChekedOut($user, $details->canBeCheckIn())
					);
				}

			}
		}
		
		return $return;
	}

	private function _getFreeExternalLinks($eContentRecord, $eContentItem){
		global $configArray;
		global $user;
		$links = array();
		$addDefaultTypeLinks = false;
		if ($eContentItem != null){
			//Single usage or free
			//default links to read the title or download
			$links = array_merge($links, $this->getDefaultEContentLinks($eContentRecord, $eContentItem));
		}else{
			$eContentItems = $eContentRecord->getItems();
			foreach ($eContentItems as $item){
				//Single usage or free
				//default links to read the title or download
				$links = array_merge($links, $this->getDefaultEContentLinks($eContentRecord, $item));
				$links[ArrayUtils::getLastKey($links)]['item_type'] = $item->item_type;
			}
		}
		
		return $links;
	}
	
	private function _getCheckedOutEContentLinks($eContentRecord, $eContentItem, $eContentCheckout){
		global $configArray;
		global $user;
		$links = array();
		$addDefaultTypeLinks = false;
		if ($eContentItem != null){
			if ($eContentRecord->accessType == 'acs' && ($eContentItem->item_type == 'epub' || $eContentItem->item_type == 'pdf')){
				//Protected by ACS server
				//Links to read the title online or checkout from ACS server
				if ($eContentItem->item_type == 'pdf'){
					$links = array_merge($links, $this->_getACSPdfLinks($eContentItem, $eContentCheckout));
				}elseif ($eContentItem->item_type == 'epub'){
					$links = array_merge($links, $this->_getACSEpubLinks($eContentItem, $eContentCheckout));
				}
			}else{
				//Single usage or free
				//default links to read the title or download
				$links = array_merge($links, $this->getDefaultEContentLinks($eContentRecord, $eContentItem));
			}
		}else{
			$eContentItems = $eContentRecord->getItems();
			foreach ($eContentItems as $item){
				if ($eContentRecord->accessType == 'acs' && ($item->item_type == 'epub' || $item->item_type == 'pdf')){
					//Protected by ACS server
					//Links to read the title online or checkout from ACS server
					if ($item->item_type == 'pdf'){
						$links = array_merge($links, $this->_getACSPdfLinks($item, $eContentCheckout));
						if($links[0]['url']==null)
						{
							unset($links[0]);
							$links = array_merge($links, $this->getDefaultEContentLinks($eContentRecord, $item));
						}
					}elseif ($item->item_type == 'epub'){
						$links = array_merge($links, $this->_getACSEpubLinks($item, $eContentCheckout));
					}
				}else{
					//Single usage or free
					//default links to read the title or download
					$links = array_merge($links, $this->getDefaultEContentLinks($eContentRecord, $item));
				}
				$links[ArrayUtils::getLastKey($links)]['item_type'] = $item->item_type;
			}
		}
		
		//Add a link to return the title
		if ($eContentCheckout->downloadedToReader == 0){
			$links[] = array(
							'text' => 'Return&nbsp;Now',
							'onclick' => "if (confirm('Are you sure you want to return this title?')){returnEpub('{$configArray['Site']['path']}/EcontentRecord/{$eContentRecord->id}/ReturnTitle')};return false;",
							'typeReturn' => 0
			);
		}else{
			$links[] = array(
				'text' => 'Return&nbsp;Now',
				'onclick' => "alert('Please return this title from your digital reader.');return false;",
				'typeReturn' => 1
			);
		}
		return $links;
	}

	public function placeHold($id, $user, $formatId = NULL)
	{
		$id = str_ireplace("econtentrecord", "", $id);
		$return = array();
		$eContentRecord = new EContentRecord();
		$eContentRecord->id = $id;
		if (!$eContentRecord->find(true)){
			$return['result'] = false;
			$return['message'] = "Could not find a record with an id of $id";
		}else{
			$return['title'] = $eContentRecord->title;
			
			$econtentDetails = EcontentDetailsFactory::get($eContentRecord);
			if($econtentDetails !== false)
			{	
				if($eContentRecord->isOverDrive())
				{
					$result = $econtentDetails->placeHold($user);
				}
				else
				{
					$result = $econtentDetails->placeHold($user);
				}
				
				if($result === false)
				{
					$return['result'] = false;
					$return['message'] = "The item could not be placed on hold.";
				}
				else
				{
					$return['result'] = true;
					$return['message'] = "The item has been placed on hold successfully";
				}
			}
			else
			{
				//Check to see if the user already has a hold placed
				$holds = new EContentHold();
				$holds->userId = $user->id;
				$holds->recordId = $id;
				$holds->whereAdd("(status = 'active' or status = 'suspended' or status ='available')");
				$holds->find();
				if ($holds->N > 0)
				{
					$return['result'] = false;
					$return['message'] = "That record is already on hold for you, unable to place a second hold.";
				}
				else
				{
					//Check to see if the user already has the record checked out
					$checkouts = new EContentCheckout();
					$checkouts->userId = $user->id;
					$checkouts->status = 'out';
					$checkouts->recordId = $id;
					$checkouts->find();
					if ($checkouts->N > 0)
					{
						$return['result'] = false;
						$return['message'] = "That record is already checked out to you, unable to place a hold.";
					}
					else
					{
						//Check to see if there are any available copies and then checkout the record rather than placing a hold
						$holdings = $this->getHolding($id);
						$holdingsSummary = $this->getStatusSummary($id, $holdings);
						if ($holdingsSummary['availableCopies'] > 0 || $eContentRecord->accessType == 'free')
						{
							//The record can be checked out directly
							$ret = $this->checkoutRecord($id, $user);
							return $ret;
						}
						else
						{
							//Place the hold for the user
							$hold = new EContentHold();
							$hold->userId = $user->id;
							$hold->recordId = $id;
							$hold->status = 'active';
							$hold->datePlaced = time();
							$hold->dateUpdated = time();
							if ($hold->insert())
							{
								$return['result'] = true;
								$holdPosition = $this->_getHoldPosition($hold);
								$return['message'] = "Your hold was successfully placed, you are number {$holdPosition} in the queue.";
								
								//Record that the record had a hold placed on it
								$this->recordEContentAction($id, "Place Hold", $eContentRecord->accessType);
							}
						}
					}
				}
			}
		}
		if ($return['result'] == true){
			//Make a call to strands to update that the item was placed on hold
			global $configArray;
			global $user;
			if (isset($configArray['Strands']['APID']) && $user->disableRecommendations == 0){
				$strandsUrl = "http://bizsolutions.strands.com/api2/event/addshoppingcart.sbs?needresult=true&apid={$configArray['Strands']['APID']}&item=econtentRecord{$id}&user={$user->id}";
				$ret = file_get_contents($strandsUrl);
			}

			// Log the usageTracking data
			UsageTracking::logTrackingData('numHolds');
		}
		return $return;
	}

	public function cancelHold($id)
	{
		global $user;

		$econtentRecord = new EContentRecord();
		$econtentRecord->id = $id;
		$econtentRecord->find(true);
		
		
		$details = EcontentDetailsFactory::get($econtentRecord);
		if($details !== false)
		{
			$result = $details->cancelHold($user);			
			if($result === true)
			{
				return array(
						'title' => '',
						'result' => true,
						'message' => 'Your hold was cancelled successfully.');
			}
			else
			{
				return array(
						'title' => '',
						'result' => true,
						'message' => 'Unabled to cancel your hold');
			}
		}
		
		//Check to see if there is an existing hold for the record
		$record = new EContentRecord();
		$record->id = $id;
		if ($record->find(true)){
			$title = $record->title;
			$hold = new EContentHold();
			$hold->recordId = $id;
			$hold->userId = $user->id;
			$hold->whereAdd("status in ('active', 'suspended')");
			$hold->find();
			if ($hold->N > 0){
				$hold->fetch();
				$hold->status = 'cancelled';
				$hold->dateUpdated = time();
				$ret = $hold->update();
				if ($ret == 1){
					$this->processHoldQueue($id);
					return array(
					      'title' => $title,
					      'result' => true,
					      'message' => 'Your hold was cancelled successfully.');
				}else{
					return array(
					      'title' => $title,
					      'result' => false,
					      'message' => 'Unable to update your hold.');
				}
			}else{ 
				return array(
				      'title' => $title,
				      'result' => true,
				      'message' => 'Sorry, but we could not find a hold for you for that title.');
			}
		}else{
			return array(
				      'title' => '',
				      'result' => false,
				      'message' => 'Could not find a record with that title.');
		}
	}
	
	public function reactivateHold($id){
		global $user;
		//Check to see if there is an existing hold for the record
		$record = new EContentRecord();
		$record->id = $id;
		if ($record->find(true)){
			$title = $record->title;
			$hold = new EContentHold();
			$hold->recordId = $id;
			$hold->userId = $user->id;
			$hold->status = 'suspended';
			$hold->find();
			if ($hold->N > 0){
				$hold->fetch();
				$hold->status = 'active';
				$hold->dateUpdated = time();
				$ret = $hold->update();
				if ($ret == 1){
					$this->processHoldQueue($id);
					return array(
					      'title' => $title,
					      'result' => true,
					      'message' => 'Your hold was activated successfully.');
				}else{
					return array(
					      'title' => $title,
					      'result' => true,
					      'message' => 'Unable to activate your hold.');
				}
			}else{ 
				return array(
				      'title' => $title,
				      'result' => true,
				      'message' => 'Sorry, but we could not find a hold for you for that title.');
			}
		}else{
			return array(
				      'title' => '',
				      'result' => false,
				      'message' => 'Could not find a record with that title.');
		}
	}
	
	public function suspendHolds($ids, $dateToReactivate){
		global $user;
		$result = array();
		foreach ($ids as $id)
		{
			$eContentRecord = new EContentRecord();
			$eContentRecord->id = $id;
			if ($eContentRecord->find(true)){
				//Find the hold for the record
				$hold = new EContentHold();
				$hold->recordId = $id;
				$hold->userId = $user->id;
				$hold->status = 'active';
				if ($hold->find(true)){
					$hold->status = 'suspended';
					$hold->reactivateDate = $dateToReactivate;
					$hold->dateUpdated = time();
					$ret = $hold->update();
					if ($ret == 1){
						$result[$id] = array(
							'success' => true,
							'title' => $eContentRecord->title,
							'error' => "The hold was suspended."
						);
					}else{
						$result[$id] = array(
							'success' => false,
							'title' => $eContentRecord->title,
							'error' => "Could not suspend the hold."
						);
					}
				}else{
					$result[$id] = array(
						'success' => false,
						'title' => $eContentRecord->title,
						'error' => "Could not find an active hold to suspend."
					);
				}
			}else{
				$result[$id] = array(
					'success' => false,
					'error' => "Could not find a record with that id"
				);
			}
		}
		return $result;
	}

	public function checkoutRecord($econtentRecord, $user, $formatId = NULL)
	{
		global $configArray;
		
		$econtentDetails = EcontentDetailsFactory::get($econtentRecord);	
		
		if($econtentDetails !== false)
		{
			
			if($econtentRecord->isOverDrive())
			{
				$result = $econtentDetails->checkout($user, $formatId);
			}
			else
			{
				$result = $econtentDetails->checkout($user);
			}
			
			
			if($result === false)
			{
				$return['result'] = false;
				$return['message'] = "The item could not be checked out.";
			}
			else
			{
				$return['result'] = true;
				$return['message'] = "The item has been checked out.";
			}
			return $return;
		}

		$id = $econtentRecord->id;
		$return = array();
		
		$return['title'] = $econtentRecord->title;
		//Check to see if the user already has the record checked out
		$checkouts = new EContentCheckout();
		$checkouts->userId = $user->id;
		$checkouts->status = 'out';
		$checkouts->recordId = $id;
		$checkouts->find();
		
		if ($checkouts->N > 0){
			$return['result'] = false;
			$return['message'] = "That record is already checked out to you, unable to check it out again.";
		}else{
			//Check to see if the record is on hold for the current user
			$holds = new EContentHold();
			$holds->userId = $user->id;
			$holds->recordId = $id;
			$holds->whereAdd("status != 'filled' AND status != 'cancelled'");
			$checkoutRecord = true;
			if ($holds->find(true)){
				if ($holds->status == 'available'){
					$checkoutRecord = true;
					$holds->status = 'filled';
					$holds->dateUpdated = time();
					$ret = $holds->update();
					$checkoutRecord = $ret == 1 ;
				}else{
					$checkoutRecord = false;
					$return['result'] = false;
					$return['message'] = "This title is already on hold for you.";
				}
			}else{
				//Check to see if there are any available copies
				$holdings = $this->getHolding($id);
				$statusSummary = $this->getStatusSummary($id, $holdings);
				if ($statusSummary['availableCopies'] == 0)
				{
					$return['result'] = false;
					$return['message'] = "There are no available copies of this title, please place a hold instead.";
				}
				else
				{
					$checkoutRecord = true;
				}
			}
				
			if ($checkoutRecord){
				//Checkout the record to the user
				$checkout = new EContentCheckout();
				$checkout->userId = $user->id;
				$checkout->recordId = $id;
				$checkout->status = 'out';
				$checkout->dateCheckedOut = time();
				$loanTerm = $configArray['EContent']['loanTerm'];
				$checkout->dateDue = time() + $loanTerm * 24 * 60 * 60; //Allow titles to be checked our for 3 weeks

				if ($checkout->insert()){
					$return['result'] = true;
					$return['message'] = "The title was checked out to you successfully.  You may read it from the My eContent page within your account.";
					
					//Record that the record was checked out
					$this->recordEContentAction($id, "Checked Out", $econtentRecord->accessType);
					
					//Add the records to the reading history for the user 
					if ($user->trackReadingHistory == 1){
						$this->addRecordToReadingHistory($econtentRecord, $user);
						
					}
				}
			}
		}
		return $return;
	}
	
	public function addRecordToReadingHistory($eContentRecord, $user){
		//Get the resource for the record
		require_once('services/MyResearch/lib/Resource.php');
		$resource = new Resource();
		$resource->record_id = $eContentRecord->id;
		$resource->source = 'eContent';
		if (!$resource->find(true)){
			$resource->title = $eContentRecord->title;
			$resource->author = $eContentRecord->author;
			$resource->format = 'EMedia';
			$resource->format_category = $eContentRecord->format_category();
			$ret = $resource->insert();
		}
		//Check to see if there is an existing entry
		require_once 'sys/ReadingHistoryEntry.php';
		$readingHistoryEntry = new ReadingHistoryEntry();
		$readingHistoryEntry->userId = $user->id;
		$readingHistoryEntry->resourceId = $resource->id;
		if (!$readingHistoryEntry->find(true)){
			$readingHistoryEntry->firstCheckoutDate = date('Y-m-d');
			$readingHistoryEntry->lastCheckoutDate = date('Y-m-d');
			$readingHistoryEntry->daysCheckedOut = 1;
			$ret = $readingHistoryEntry->insert();
		}else{
			$readingHistoryEntry->lastCheckoutDate = date('Y-m-d');
			$ret = $readingHistoryEntry->update();
		}
	}
	
	public function returnRecordInReadingHistory($eContentRecord, $user){
		//Get the resource for the record
		$resource = new Resource();
		$resource->record_id = $eContentRecord->id;
		$resource->source = 'eContent';
		if ($resource->find(true)){
			//Check to see if there is an existing entry
			require_once 'sys/ReadingHistoryEntry.php';
			$readingHistoryEntry = new ReadingHistoryEntry();
			$readingHistoryEntry->userId = $user->id;
			$readingHistoryEntry->resourceId = $resource->id;
			if ($readingHistoryEntry->find(true)){
				$readingHistoryEntry->lastCheckoutDate = date('Y-m-d');
				$ret = $readingHistoryEntry->update();
			}
		}
	}

	public function getMyEContent($user){
		global $user;
		global $configArray;
		$eContent = array();
		$myTransactions = $this->getMyTransactions($user);
		$eContent['checkedOut'] = $myTransactions['transactions'];

		$myHolds = $this->getMyHolds($user);
		$eContent['availableHolds'] = $myHolds['holds']['available'];
		$eContent['unavailableHolds'] = $myHolds['holds']['unavailable'];
		
		$myWishList = $this->getMyWishList($user);
		$eContent['wishlist'] = $myWishList['items'];
		
		/*require_once('sys/eContent/EContentHistoryEntry.php');
		$user_epub_history = new EContentHistoryEntry();
		$user_epub_history->userId = $user->id;
		$user_epub_history->orderBy('openDate DESC, title ASC');
		$econtentRecord = new EContentRecord();
		$user_epub_history->joinAdd($econtentRecord, 'INNER');
		$user_epub_history->whereAdd("econtent_record.accessType = 'free'");
		$user_epub_history->find();
		$freeContent = array();
		while ($user_epub_history->fetch()){
			$freeItem = clone $user_epub_history;
			$freeItem->links[] = array(
				'url' => $configArray['Site']['path'] . '/EcontentRecord/' . $freeItem->recordId ,
				'text' => 'Read'
			);
			
			$freeContent[$freeItem->id] = $freeItem;
		}
		$eContent['free'] = $freeContent;*/
		
		return $eContent;
	}
	
	public function getMyWishList($user)
	{
		global $configArray;
		//Get wishlist 
		$wishListEntry = new EContentWishList();
		$wishListEntry->userId = $user->id;
		$wishListEntry->status = 'active';
		$wishListEntry->orderBy('title ASC');
		$econtentRecord = new EContentRecord();
		$wishListEntry->joinAdd($econtentRecord, 'INNER');
		$wishListEntry->find();
		$wishList = array();
		while ($wishListEntry->fetch()){
			$wishListItem = clone $wishListEntry;
			$wishListItem->links[] = array(
				'url' => $configArray['Site']['path'] . '/EcontentRecord/' . $wishListEntry->recordId . '/RemoveFromWishList' ,
				'text' => 'Remove&nbsp;From&nbsp;Wish&nbsp;List'
			);
			$wishListItem->recordId = 'econtentRecord' . $wishListItem->recordId;
			$wishList[$wishListItem->id] = $wishListItem;
		}
		
		//OverDrive
		$overDriveAPIServ = new OverDriveServicesAPI();
		
		$mcService = new MemcacheServices();
		$results = $mcService->call($overDriveAPIServ, "getPatronCirculation", array($user->getBarcode()), $user->getBarcode()."_getPatronCirculationODAPIServices", 30);
		if ($results !== false)
		{
			foreach ($results->WishList as $item)
			{
				unset($wishListItem);
				
				$id = $item['ItemId'];
				$eContentRecord = OverDriveUtils::getEcontentRecordFromOverDriveID($id);
				
				$wishListItem->recordId = $eContentRecord->id;
				$wishListItem->source = 'OverDrive';
				$wishListItem->title = $eContentRecord->getTitle();
				$wishListItem->links[] = array(
						'url' => $configArray['Site']['path'] . '/EcontentRecord/' . $eContentRecord->id . '/RemoveFromWishList' ,
						'text' => 'Remove&nbsp;From&nbsp;Wish&nbsp;List'
				);
				$wishList[$eContentRecord->id] = $wishListItem;
			}
		}
		
		$wishList['items'] = $wishList;
		
		return $wishList;
	}

	public function getWaitList($id){
		$eContentHold = new EContentHold();
		$eContentHold->recordId = $id;
		$eContentHold->status = 'active';
		$eContentHold->find();
		return $eContentHold->N;
	}

	private function getDefaultEContentLinks($eContentRecord, $eContentItem){
		global $configArray;
		global $user;

		$links = array();
		if (strcasecmp($eContentItem->item_type, 'epub') == 0){
			//Read in DCL Viewer
			$links[] = array(
							'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Viewer?item={$eContentItem->id}",
							'text' => 'Read&nbsp;EPUB&nbsp;Online',
			);

			if ($eContentRecord->source != "Gale Group"){
				//Download link
				$links[] = array(
								'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Download?item={$eContentItem->id}",
								'text' => 'Download&nbsp;EPUB',
				);
			}
		}elseif (strcasecmp($eContentItem->item_type, 'mp3') == 0){
			//Read online (will launch PDF viewer)
			$links[] = array(
							'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Viewer?item={$eContentItem->id}",
							'text' => 'Listen&nbsp;Online',
			);
			$links[] = array(
							'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Download?item={$eContentItem->id}",
							'text' => 'Download&nbsp;MP3',
			);
		}elseif (strcasecmp($eContentItem->item_type, 'pdf') == 0){
			//Read online (will launch PDF viewer)
			$links[] = array(
							'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Download?item={$eContentItem->id}",
							'text' => 'Open&nbsp;PDF',
			);
		}elseif (strcasecmp($eContentItem->item_type, 'kindle') == 0){
			//Download book to device
			$links[] = array(
							'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Download?item={$eContentItem->id}",
							'text' => 'Download&nbsp;Kindle&nbsp;Book',
			);
		}elseif (strcasecmp($eContentItem->item_type, 'plucker') == 0){
			//Download book to device
			$links[] = array(
							'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Download?item={$eContentItem->id}",
							'text' => 'Download&nbsp;Plucker&nbsp;Book',
			);
		}elseif (strcasecmp($eContentItem->item_type, 'externalMP3') == 0){
			if ($eContentRecord->source == 'Freegal'){
				if ($user){
					$url = $eContentItem->link;
					$url = str_replace("{patronBarcode}", $user->cat_username, $url);
					$url = str_replace("{patronPin}", $user->cat_password, $url);
					//Link to Freegal
					$links[] = array(
									'url' => $url,
									'text' => 'Get&nbsp;MP3&nbsp;From&nbsp;Freegal',
					);
				}else{
					$links[] = array(
									'url' => $configArray['Site']['path'] . "/MyResearch/Login",
									'text' => 'Login&nbsp;to&nbsp;download&nbsp;from&nbsp;Freegal',
					);
				}
			}else{
				$links[] = array(
							'url' => $eContentItem->link,
							'text' => 'Access&nbsp;MP3',
				);
			}
		}
		elseif (in_array($eContentItem->item_type, array('externalLink', 'interactiveBook')))
		{
			$freeEContentRecordService = new FreeEcontentRecordServices();
			$format = $freeEContentRecordService->getFormatType($eContentRecord);
			
			$links[] = array(
							'url' =>  $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Link?itemId={$eContentItem->id}",
							'text' => 'Access&nbsp;'.$format,
			);
		}
		return $links;
	}

	function getOnHoldEContentLinks($eContentHold){
		global $configArray;
		$links = array();
		//Link to cancel hold
		$links[] = array(
			'text' => 'Cancel&nbsp;Hold',
			'onclick' => "if (confirm('Are you sure you want to cancel this title?')){cancelEContentHold('{$configArray['Site']['path']}/EcontentRecord/{$eContentHold->recordId}/CancelHold')};return false;",
		
		);
		//Link to reactivate hold
		if ($eContentHold->status == 'suspended'){
			$links[] = array(
				'text' => 'Reactivate&nbsp;Hold',
				'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentHold->recordId}/ReactivateHold",
				'onclick' => "reactivateEContentHold('{$configArray['Site']['path']}/EcontentRecord/{$eContentHold->recordId}/ReactivateHold');return false;",
			);
		}
		//Link to check out (if available)
		if ($eContentHold->status == 'available'){
			$links[] = array(
				'text' => 'Checkout',
				'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentHold->recordId}/Checkout",
			
			);
		}

		return $links;
	}

	function returnRecord($id){
		global $user;
		$logger = new Logger();
		
		$detailsEcontent = EcontentDetailsFactory::getById($id);
		if($detailsEcontent !== false)
		{
			$result = $detailsEcontent->checkin($user);
			if($result !== false)
			{
				return array('success' => true, 'message' => "The title was returned successfully.");
			}
			return array('success' => false, 'message' => "Could not return the item");
		}
		
		//Get the item information for the record
		require_once('sys/eContent/EContentCheckout.php');
		$checkout = new EContentCheckout();
		$checkout->userId = $user->id;
		$checkout->recordId = $id;
		$checkout->status = 'out';
		
		$return = array();
		//$trans->whereAdd('timeReturned = null');
		if ($checkout->find(true)){
			$output = array();
			$checkout->dateReturned = time();
			$checkout->status = 'returned';
			$ret = $checkout->update();
				
			if ($ret != 0){
				$this->processHoldQueue($id);
				$eContentRecord = new EContentRecord();
				$eContentRecord->id = $id;
				$eContentRecord->find(true);
				//Record that the title was checked in
				$this->recordEContentAction($id, "Checked In", $eContentRecord->accessType);
				
				//$eContentRecord->saveToSolr();
				
				$return = array('success' => true, 'message' => "The title was returned successfully.");
			}else{
				$return = array('success' => false, 'message' => "Could not return the item");
			}

			$output['database-response'] = $ret;
		}else{
			$logger->log("Could not find a checked out item for that title in the database.", PEAR_LOG_INFO);
			$return = array('success' => false, 'message' => "Could not find a checked out item for that title in the database.  It may have already been returned.");
		}
		return $return;
	}

	function processHoldQueue($id){
		//Check to see if there are any copies available for the next person
		$holdings = $this->getHolding($id);
		$holdingSummary = $this->getStatusSummary($id, $holdings);
		if ($holdingSummary['availableCopies'] >= 1){
		
			$eContentHold = new EContentHold();
			$eContentHold->recordId = $id;
			$eContentHold->status = 'active';
			$eContentHold->orderBy('datePlaced ASC');
			$eContentHold->limit(0, 1);
			if ($eContentHold->find(true)){
				//The next user in the list should get the hold
				$eContentHold->status = 'available';
				$eContentHold->dateUpdated = time();
				$eContentHold->update();
			}
		}
	}
	
	private function _getACSEpubLinks($eContentItem, $eContentCheckout){
		require_once 'sys/AdobeContentServer.php';
		global $configArray;
		$links = array();
		
		if($configArray['EContent']['Read Online'])
		{
			//Read in DCL Viewer
			$links[] = array(
				'url' => $configArray['Site']['path'] . "/EcontentRecord/{$eContentItem->recordId}/Viewer?item={$eContentItem->id}",
				'text' => 'Read&nbsp;Online',
			);
		}

		//Download link
		$downloadLink = AdobeContentServer::mintDownloadLink($eContentItem, $eContentCheckout);
		$links[] = array(
			'url' => $downloadLink,
			'text' => 'Download',
		);
		return $links;
	}
	
	private function _getACSPdfLinks($eContentItem, $eContentCheckout){
		require_once 'sys/AdobeContentServer.php';
		global $configArray;
		$links = array();
		
		//Download link
		$downloadLink = AdobeContentServer::mintDownloadLink($eContentItem, $eContentCheckout);
		$links[] = array(
			'url' => $downloadLink,
			'text' => 'Download',
			'onclick'=>"location.reload();"
		);
		return $links;
	}

	public function isRecordCheckedOutToUser($id){
		require_once('sys/eContent/EContentCheckout.php');
		global $user;
		$checkout = new EContentCheckout();
		$checkout->recordId = $id;
		$checkout->userId = $user->id;
		$checkout->status = 'out';
		if ($checkout->find(true)){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Track that an e-pub file was opened in the user's reading history.
	 */
	public function recordEContentAction($id, $action, $accessType){
		global $user;

		require_once('sys/eContent/EContentHistoryEntry.php');

		global $configArray;
		if (isset($configArray['Strands']['APID']) && $user->disableRecommendations == 0 && $action == "Checked Out"){
			//Check to see if this is the first time the user has read the title and if so record the entry in strands
			$entry = new EContentHistoryEntry();
			$entry->userId = $user->id;
			$entry->recordId = $id;
			$entry->find();
			if ($entry->N == 0){
				$orderId = $user->id . '_' . time() ;
				$strandsUrl = "http://bizsolutions.strands.com/api2/event/purchased.sbs?needresult=true&apid={$configArray['Strands']['APID']}&item=econtentRecord{$id}::0.00::1&user={$user->id}&orderid={$orderId}";
				$ret = file_get_contents($strandsUrl);
			}
		}

		$entry = new EContentHistoryEntry();
		$entry->userId = $user->id;
		$entry->recordId = $id;
		$entry->action = $action;
		$entry->accessType = $accessType;
		//Open date will be filled out automatically.
		$entry->insert();
	}
	
	public function getAccountSummary()
	{
		global $user, $configArray;
		$accountSummary = array();
		if ($user)
		{
			$totalCheckOuts = 0;
			$totalUnAvailableHolds = 0;
			$totalWishList = 0;
			
			//3M??
			$threeMAPI = new ThreeMAPI();
			$results = $threeMAPI->getPatronCirculation($user->getBarcode());
			$totalCheckOuts = count($results->Checkouts[0]);
			$totalUnAvailableHolds = count($results->Holds[0]);
			$totalAvailableHolds = count($results->Reserves[0]);
			
			//OverDrive??
			$overDriveAPIServ = new OverDriveServicesAPI();
			
			$mcService = new MemcacheServices();
			$results = $mcService->call($overDriveAPIServ, "getPatronCirculation", array($user->getBarcode()), $user->getBarcode()."_getPatronCirculationODAPIServicesCount", 300);

			$totalCheckOuts += count($results->Checkouts);
			$totalUnAvailableHolds += count($results->Holds);
			$totalAvailableHolds += count($results->AvailableHolds);
			$totalWishList += count($results->WishList);
			
			/*$totalCheckOuts = 0;
			$totalUnAvailableHolds = 0;
			$totalAvailableHolds = 0;*/
			
			//Get a count of checked out items
			$eContentCheckout = new EContentCheckout();
			$eContentCheckout->status = 'out';
			$eContentCheckout->userId = $user->id;
			$eContentCheckout->find();
			$accountSummary['numEContentCheckedOut'] = $totalCheckOuts + $eContentCheckout->N;
			
			//Get a count of available holds
			$eContentHolds = new EContentHold();
			$eContentHolds->status = 'available';
			$eContentHolds->userId = $user->id;
			$eContentHolds->find();
			$accountSummary['numEContentAvailableHolds'] = $totalAvailableHolds + $eContentHolds->N;
			
			//Get a count of unavailable holds
			$eContentHolds = new EContentHold();
			$eContentHolds->whereAdd("status IN ('active', 'suspended')");
			$eContentHolds->userId = $user->id;
			$eContentHolds->find();
			$accountSummary['numEContentUnavailableHolds'] = $totalUnAvailableHolds + $eContentHolds->N;
			
			//Get a count of items on the wishlist
			$eContentWishList = new EContentWishList();
			$eContentWishList->status = 'active';
			$eContentWishList->userId = $user->id;
			$eContentWishList->find();
			$accountSummary['numEContentWishList'] = $totalWishList + $eContentWishList->N;
		}
		
		return $accountSummary;
	}
	
	function addToWishList($id, $user){
		$wishlistEntry = new EContentWishList();
		$wishlistEntry->userId = $user->id;
		$wishlistEntry->recordId = $id;
		$wishlistEntry->status = 'active';
		
		if ($wishlistEntry->find(true)){
			//The record was already added to the database
		}else{
			//Add to the database
			$wishlistEntry->dateAdded = time();
			$wishlistEntry->insert();
		}
		return true;
	}
}