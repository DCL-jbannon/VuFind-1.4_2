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
	
	public function __construct(IEContentRecord $econtentRecord, IThreeMAPI $threeMAPI = NULL,
																 IThreeMUtils $threeMUtils = NULL)
	{
		$this->econtentRecord = $econtentRecord;
		
		if(!$threeMAPI) $threeMAPI = new ThreeMAPI();
		$this->threeMAPI = $threeMAPI;
		
		if(!$threeMUtils) $threeMUtils = new ThreeMUtils();
		$this->threeMUtils = $threeMUtils;
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
		
	public function isCancelHoldAvailable($patronId)
	{
		$threeMId = $this->getId();
		$actual = $this->threeMAPI->getPatronCirculation($patronId);
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
	
	public function isCheckedOutByPatron($patronId)
	{
		$threeMId = $this->getId();
		$actual = $this->threeMAPI->getPatronCirculation($patronId);
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

	public function getFormatType()
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
	
	public function checkout($patronId)
	{
		$id = $this->getId();
		return $this->threeMAPI->checkout($id, $patronId);
	}
	
	/**
	 * Check In a eContentRecord from 3M
	 * @see IEcontentRecordDetails::checkin()
	 */
	public function checkin($patronId)
	{
		$id = $this->getId();
		return $this->threeMAPI->checkin($id, $patronId);
	}
	
	public function placeHold($patronId)
	{
		$id = $this->getId();
		return $this->threeMAPI->placeHold($id, $patronId);
	}
	
	/**
	 * Return true or false
	 * @see IEcontentRecordDetails::cancelHold()
	 */
	public function cancelHold($patronId)
	{
		$id = $this->getId();
		return $this->threeMAPI->cancelHold($id, $patronId);
	}
	
	public function getAccessUrls($patronId = NULL)
	{
		return $this->econtentRecord->sourceUrl;
	}
	
	public function getSize()
	{
		$threeMId = $this->getId();
		$result = $this->threeMAPI->getItemDetails($threeMId);
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
	
	//Private Methods
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
		return $this->threeMAPI->getItemDetails($threeMId);
	}
	
	private function getId()
	{
		return $this->threeMUtils->get3MId($this->econtentRecord);
	}
}