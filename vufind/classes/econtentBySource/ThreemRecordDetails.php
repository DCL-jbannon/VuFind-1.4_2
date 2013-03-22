<?php
require_once dirname(__FILE__).'/../interfaces/IEcontentRecordDetails.php';
require_once dirname(__FILE__).'/../Utils/ThreeMUtils.php';
require_once dirname(__FILE__).'/../Utils/EContentFormatType.php';
require_once dirname(__FILE__).'/../API/3M/ThreeMAPI.php';
require_once dirname(__FILE__).'/EcontentRecordConstants.php';
require_once dirname(__FILE__).'/BaseEcontentDetails.php';

class ThreemRecordDetails extends BaseEcontentDetails implements IEcontentRecordDetails
{
	
	private $threeMAPI;
	private $threeMUtils;
	private $memcacheServices;
	
	public function __construct(IEContentRecord $econtentRecord, 
			                    IThreeMAPI $threeMAPI = NULL,
								IThreeMUtils $threeMUtils = NULL,
								IMemcacheServices $memcacheServices = NULL)
	{
		$this->econtentRecord = $econtentRecord;
		
		if(!$threeMAPI) $threeMAPI = new ThreeMAPI();
		$this->threeMAPI = $threeMAPI;
		
		if(!$threeMUtils) $threeMUtils = new ThreeMUtils();
		$this->threeMUtils = $threeMUtils;
		
		if(!$memcacheServices) $memcacheServices = new MemcacheServices();
		$this->memcacheServices = $memcacheServices;
	}
	
	public function getTotalCopies()
	{
		$result = $this->callGetItemDetailsMethod();
		return (string)$result->TotalCopies;
	}
	
	public function getAvailableCopies()
	{
		$result = $this->callGetItemDetailsMethod();
		return (string)$result->AvailableCopies;
	}
	
	public function isCheckOutAvailable()
	{
		$result = $this->callGetItemDetailsMethod();
		return $this->itIsTrueValue($result->CanCheckout);
	}
	
	public function isPlaceHoldAvailable()
	{
		$result = $this->callGetItemDetailsMethod();
		return $this->itIsTrueValue($result->CanHold);
	}
		
	public function isCancelHoldAvailable(IUser $user)
	{
		$patronId = $this->getUserId($user);
		$threeMId = $this->getId();
		$actual = $this->getPatronCirculation($patronId);
		foreach ($actual->Holds as $itemOnHolds)
		{
			if($itemOnHolds->Item->ItemId == $threeMId)
			{
				return true;
			}
		}
		return false;
	}
	
	public function isAddWishListAvailable(){return false;}
	public function isAccessOnlineAvailable(){return false;}
	public function getWishListSize(){return 0;}
	
	public function getHoldLength()
	{
		$results = $this->callGetItemDetailsMethod();
		return (string)$results->OnHoldCount;
	}
	
	public function isCheckedOutByPatron(IUser $user)
	{
		$patronId = $this->getUserId($user);
		$threeMId = $this->getId();
		$actual = $this->getPatronCirculation($patronId);
		if(isset($actual->Checkouts))
		{
			
			foreach ($actual->Checkouts as $itemsCheckedOut)
			{
				foreach ($itemsCheckedOut as $itemCheckedOut)
				{
					if($itemCheckedOut->ItemId == $threeMId)
					{
						return true;
					}
				}
			}
		}
		return false;
	}

	public function getFormatType($itemIndex = 1)
	{
		return EContentFormatType::eBook;	
	}
	
	public function getFormats()
	{
		return "EPUB";
	}
	
	/**
	 * Define how to load the Status Summaries of this record via AJAX
	 * @see IEcontentRecordDetails::getMethodLoadStatusSummaries()
	 */
	public function getMethodLoadStatusSummaries()
	{
		return EcontentRecordConstants::MethodUniqueToLoadStatusSummaries;
	}
	
	public function getMsgAvailable()
	{
		return "Available from 3M";
	}
	public function getMsgCheckedOut(){return false;}
	public function getMsgCheckedOutToYou(){return false;}
	
	public function checkout(IUser $user)
	{
		$patronId = $this->getUserId($user);
		$id = $this->getId();
		return $this->threeMAPI->checkout($id, $patronId);
	}
	
	/**
	 * Check In a eContentRecord from 3M
	 * @see IEcontentRecordDetails::checkin()
	 */
	public function checkin(IUser $user)
	{
		$patronId = $this->getUserId($user);
		$id = $this->getId();
		return $this->threeMAPI->checkin($id, $patronId);
	}
	
	public function placeHold(IUser $user)
	{
		$patronId = $this->getUserId($user);
		$id = $this->getId();
		return $this->threeMAPI->placeHold($id, $patronId);
	}
	
	/**
	 * Return true or false
	 * @see IEcontentRecordDetails::cancelHold()
	 */
	public function cancelHold(IUser $user)
	{
		$patronId = $this->getUserId($user);
		$id = $this->getId();
		return $this->threeMAPI->cancelHold($id, $patronId);
	}
	
	public function getAccessUrls(IUser $user)
	{
		return $this->econtentRecord->getSourceUrl();
	}
	
	public function getSize($itemIndex = 1)
	{
		$result = $this->callGetItemDetailsMethod();
		return (string)$result->Size;
	}
	
	public function showAddItemButton()
	{
		return false;
	}
	
	public function getUsageNotesMessage()
	{
		return false;
	}
	
	public function showCancelHoldLinkAvailableHolds()
	{
		return false;
	}
	
	public function canSuspendHolds()
	{
		return false;
	}
	
	public function canBeCheckIn(){return true;}
	
	public function getNumItems(){return 1;}
	
	public function removeWishList(IUser $user){return false;}
	public function addWishList(IUser $user){return false;}
	
	//Private Methods
	public function getPatronCirculation($patronId)
	{
		return $this->memcacheServices->call($this->threeMAPI, "getPatronCirculation", array($patronId), "3MGetPatronCirculation_".$patronId, 30);
	}
	
	private function itIsTrueValue($value)
	{
		if ($value == "TRUE")
		{
			return true;
		}
		return false;
	}
	
	private function callGetItemDetailsMethod()
	{
		$threeMId = $this->getId();
		return $this->memcacheServices->call($this->threeMAPI, "getItemDetails", array($threeMId), "3MGetItemDetails_".$threeMId, 300);
	}
	
	private function getId()
	{
		return $this->threeMUtils->get3MId($this->econtentRecord);
	}
	
	private function getUserId($user)
	{
		return $user->getId();
	}
}