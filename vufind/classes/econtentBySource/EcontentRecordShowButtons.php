<?php
require_once dirname(__FILE__).'/../interfaces/IEcontentRecordShowButtons.php';
require_once dirname(__FILE__).'/BaseEcontentRecordHelpers.php';

class EcontentRecordShowButtons extends BaseEcontentRecordHelpers implements IEcontentRecordShowButtons
{

	public function showCheckOut(IUser $user = NULL)
	{
		$checkOutAvailable = $this->econtentRecordDetails->isCheckOutAvailable();
		
		if(!$checkOutAvailable)
		{
			return false;
		}
		
		if($user === NULL)
		{
			return true;
		}
		
		$checkedOutByPatron = $this->econtentRecordDetails->isCheckedOutByPatron($user);
		if ($checkedOutByPatron)
		{
			return false;
		}
		return true;
	}
	
	public function showPlaceHold(IUser $user = NULL)
	{
		$placeHoldAvailable = $this->econtentRecordDetails->isPlaceHoldAvailable();
		if(!$placeHoldAvailable)
		{
			return false;
		}
		
		if($user === NULL)
		{
			return true;
		}
		
		$checkedOutByPatron = $this->econtentRecordDetails->isCheckedOutByPatron($user);
		if($checkedOutByPatron)
		{
			return false;
		}
		return true;
	}
	
	public function showAddToWishList(IUser $user = NULL)
	{
		$addToWishListAvailable = $this->econtentRecordDetails->isAddWishListAvailable();
		if(!$addToWishListAvailable)
		{
			return false;
		}
		
		if ($user !== NULL)
		{
			$checkedOutByPatron = $this->econtentRecordDetails->isCheckedOutByPatron($user);
			if ($checkedOutByPatron)
			{
				return false;
			}
			$placedHold = $this->econtentRecordDetails->isCancelHoldAvailable($user);
			if($placedHold)
			{
				return false;
			}
		}
		
		$placeHoldAvailable = $this->econtentRecordDetails->isPlaceHoldAvailable();
		$checkOutAvailable = $this->econtentRecordDetails->isCheckOutAvailable();

		if(!$placeHoldAvailable && !$checkOutAvailable)
		{
			return true;
		}
	}
	
	public function showAccessOnline(IUser $user = NULL)
	{
		return $this->econtentRecordDetails->isAccessOnlineAvailable();
	}
}
?>