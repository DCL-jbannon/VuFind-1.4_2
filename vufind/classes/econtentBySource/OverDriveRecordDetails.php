<?php
require_once dirname(__FILE__).'/../interfaces/IEcontentRecordDetails.php';
require_once dirname(__FILE__).'/BaseEcontentDetails.php';
require_once dirname(__FILE__).'/../API/OverDrive/OverDriveServicesAPI.php';
require_once dirname(__FILE__).'/../Utils/RegularExpressions.php';
require_once dirname(__FILE__).'/EcontentRecordConstants.php';
require_once dirname(__FILE__).'/../API/OverDrive/OverDriveFormatTranslation.php';

class OverDriveRecordDetails extends BaseEcontentDetails implements IEcontentRecordDetails
{
	private $overDriveServicesAPI;
	private $regExp;
	private $memcacheServices;
	
	public function __construct(IEContentRecord $econtentRecord,
							    IOverDriveServicesAPI $overDriveServicesAPI = NULL,
								IRegularExpressions $regExpr = NULL)
	{
		if(!$overDriveServicesAPI) $overDriveServicesAPI = new OverDriveServicesAPI();
		$this->overDriveServicesAPI = $overDriveServicesAPI;
		
		if(!$regExpr) $regExpr = new RegularExpressions();
		$this->regExp = $regExpr;
		
		$this->econtentRecord = $econtentRecord;
	}
	
	public function isCheckOutAvailable()
	{
		$overDriveId = $this->getId();
		$result = $this->getItemDetails($overDriveId);
		return $result->CanCheckout;
	}
	
	public function isPlaceHoldAvailable()
	{
		$overDriveId = $this->getId();
		$result = $this->getItemDetails($overDriveId);
		return $result->CanHold;
	}
	
	public function isAddWishListAvailable()
	{
		$overDriveId = $this->getId();
		$result = $this->getItemDetails($overDriveId);
		return $result->CanAddWishList;
	}
	
	public function isAccessOnlineAvailable(){return false;}
	
	public function getTotalCopies()
	{
		$overDriveId = $this->getId();
		$result = $this->getItemDetails($overDriveId);
		return $result->TotalCopies;
	}
	
	public function getAvailableCopies()
	{
		$overDriveId = $this->getId();
		$result = $this->getItemDetails($overDriveId);
		return $result->AvailableCopies;
	}
	
	public function getHoldLength()
	{
		$overDriveId = $this->getId();
		$result = $this->getItemDetails($overDriveId);
		return $result->OnHoldCount;
	}
	
	public function getWishListSize(){return 0;}
	
	public function isCancelHoldAvailable(IUser $user)
	{
		$overDriveId = $this->getId();
		$patronCirculation = $this->getPatronCirculation($user);
		foreach ($patronCirculation->Holds as $itemPlacedHold)
		{
			if($itemPlacedHold['ItemId'] == $overDriveId)
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function isCheckedOutByPatron(IUser $user)
	{
		$overDriveId = $this->getId();
		$patronCirculation = $this->getPatronCirculation($user);
		foreach ($patronCirculation->Checkouts as $itemCheckedOut)
		{
			if($itemCheckedOut['ItemId'] == $overDriveId)
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function checkout(IUser $user, $formatId = NULL)
	{
		$overDriveId = $this->getId();
		$username = $this->getUserUsername($user);
		return $this->overDriveServicesAPI->checkOut($username, $overDriveId, $formatId);
	}
	
	public function chooseFormat(IUser $user, $formatId = NULL)
	{
		$overDriveId = $this->getId();
		$username = $this->getUserUsername($user);
		return $this->overDriveServicesAPI->chooseFormat($username, $overDriveId, $formatId);
	}
	
	public function checkin(IUser $user)
	{
		return true;
	}
	
	public function placeHold(IUser $user)
	{
		$overDriveId = $this->getId();
		$username = $this->getUserUsername($user);
		$email = $user->getEmail();
		return $this->overDriveServicesAPI->placeHold($username, $overDriveId, $email);
	}
	
	public function cancelHold(IUser $user)
	{
		$overDriveId = $this->getId();
		$username = $this->getUserUsername($user);
		$this->overDriveServicesAPI->cancelHold($username, $overDriveId);
		return true;
	}
	
	public function getNumItems()
	{
		$overDriveId = $this->getId();
		$result = $this->getItemMetadata($overDriveId);
		if(isset($result->formats))
		{
			return count($result->formats);
		}
		return 0;
	}
	
	public function getFormatType($itemIndex = 1)
	{
		$overDriveId = $this->getId();
		$result = $this->getItemMetadata($overDriveId);
		if(isset($result->formats))
		{
			if(isset($result->formats[$itemIndex-1]))
			{
				return $result->formats[$itemIndex-1]->name;
			}
		}
		return "";
	}
	
	public function getFormats()
	{
		$formats = "";
		$overDriveId = $this->getId();
		$result = $this->getItemMetadata($overDriveId);
		if(isset($result->formats))
		{
			foreach ($result->formats as $format)
			{
				if(!empty($formats)) $formats .=", ";
				$formats .= $format->name;
			}
		}
		return $formats;
	}
	
	public function getFormatsInfo()
	{
		$formats = "";
		$overDriveId = $this->getId();
		$result = $this->getItemMetadata($overDriveId);
		if(isset($result->formats))
		{
			$i = 0;
			foreach ($result->formats as $format)
			{
				$formats[$i]["id"]   = OverDriveFormatTranslation::getFormatIdFromString($format->id);
				$formats[$i]["name"] = $format->name;
				$i++;
			}
		}
		return $formats;
	}
	
	public function getMsgAvailable()
	{
		return 'Available from OverDrive';
	}
	
	public function getMsgCheckedOut()
	{
		return 'Checked out in OverDrive';
	}
	
	public function getMsgCheckedOutToYou()
	{
		return false;
	}
	
	public function getUsageNotesMessage()
	{
		return false;
	}
	
	public function getAccessUrls(IUser $user)
	{
		$overDriveId = $this->getId();
		$patronCirculation = $this->getPatronCirculation($user);
		foreach ($patronCirculation->Checkouts as $itemCheckedOut)
		{
			if(preg_match("/^".$overDriveId."$/i", $itemCheckedOut['ItemId']))
			{
				if(!$itemCheckedOut['ChooseFormat'])
				{
					return $itemCheckedOut['Link'];
				}
				else
				{
					return "/EcontentRecord/".$this->econtentRecord->id."/CFormat";
				}
			}
		}
	}
	
	public function getMethodLoadStatusSummaries()
	{
		return EcontentRecordConstants::MethodUniqueToLoadStatusSummaries;
	}
	
	public function getSize($itemIndex = 1)
	{
		$overDriveId = $this->getId();
		$result = $this->getItemMetadata($overDriveId);
		if(isset($result->formats))
		{
			if(isset($result->formats[$itemIndex-1]))
			{
				return ceil(($result->formats[$itemIndex-1]->fileSize / 1024));
			}
		}
		return "--";
	}
	
	public function showAddItemButton()
	{
		return false;		
	}
	
	public function showCancelHoldLinkAvailableHolds()
	{
		return true;	
	}
	
	public function canSuspendHolds()
	{
		return false;
	}
	
	public function canBeCheckIn()
	{
		return false;
	}
	
	public function removeWishList(IUser $user)
	{
		$overDriveId = $this->getId();
		$username = $this->getUserUsername($user);
		$this->overDriveServicesAPI->removeWishList($username, $overDriveId);
		return true;
	}
	
	public function addWishList(IUser $user)
	{
		$overDriveId = $this->getId();
		$username = $this->getUserUsername($user);
		$this->overDriveServicesAPI->addToWishList($username, $overDriveId);
		return true;
	}
	
	//PRIVATES
	private function getPatronCirculation($user)
	{
		$username = $this->getUserUsername($user);
		return $this->overDriveServicesAPI->getPatronCirculation($username);
	}
	
	private function getId()
	{
		$sourceUrl = $this->econtentRecord->getSourceUrl();
		return strtoupper($this->regExp->getFieldValueFromURL($sourceUrl, "ID"));
	}
	
	private function getUserUsername($user)
	{
		return $user->getUsername();
	}
	
	private function getItemDetails($overDriveId)
	{
		return $this->overDriveServicesAPI->getItemDetails($overDriveId);
	}
	
	private function getItemMetadata($overDriveId)
	{
		return $this->overDriveServicesAPI->getItemMetadata($overDriveId);
	}
}
?>