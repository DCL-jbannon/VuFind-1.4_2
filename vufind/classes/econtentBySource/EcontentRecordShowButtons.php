<?php
require_once dirname(__FILE__).'/../interfaces/IEcontentRecordShowButtons.php';
require_once dirname(__FILE__).'/BaseEcontentRecordHelpers.php';

class EcontentRecordShowButtons extends BaseEcontentRecordHelpers implements IEcontentRecordShowButtons
{

	public function showCheckOut($patronId = NULL)
	{
		$checkOutAvailable = $this->econtentRecordDetails->isCheckOutAvailable();
		
		if(!$checkOutAvailable)
		{
			return false;
		}
		
		if($patronId === NULL)
		{
			return true;
		}
		
		$checkedOutByPatron = $this->econtentRecordDetails->isCheckedOutByPatron($patronId);
		if ($checkedOutByPatron)
		{
			return false;
		}
		return true;
	}
	
	public function showPlaceHold($patronId = NULL)
	{
		$placeHoldAvailable = $this->econtentRecordDetails->isPlaceHoldAvailable();
		if(!$placeHoldAvailable)
		{
			return false;
		}
		
		if($patronId === NULL)
		{
			return true;
		}
		
		$checkedOutByPatron = $this->econtentRecordDetails->isCheckedOutByPatron($patronId);
		if($checkedOutByPatron)
		{
			return false;
		}
		return true;
	}
	
	public function showAddToWishList($patronId = NULL)
	{
		$addToWishListAvailable = $this->econtentRecordDetails->isAddWishListAvailable();
		if(!$addToWishListAvailable)
		{
			return false;
		}
		
		if ($patronId !== NULL)
		{
			$checkedOutByPatron = $this->econtentRecordDetails->isCheckedOutByPatron($patronId);
			if ($checkedOutByPatron)
			{
				return false;
			}
			$placedHold = $this->econtentRecordDetails->isCancelHoldAvailable($patronId);
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
	
	public function showAccessOnline()
	{
		return $this->econtentRecordDetails->isAccessOnlineAvailable();
	}
}
?>