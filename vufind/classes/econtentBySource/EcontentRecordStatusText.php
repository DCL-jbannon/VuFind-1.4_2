<?php
require_once dirname(__FILE__).'/../interfaces/IEcontentRecordStatusText.php';
require_once dirname(__FILE__).'/BaseEcontentRecordHelpers.php';


class EcontentRecordStatusText extends BaseEcontentRecordHelpers implements IEcontentRecordStatusText
{
	
	const availableOnline = "Available Online";
	const checkedOut = "Checked Out";
	const checkedOutToYou = "Checked Out to you";
	const usageNote = "Must be checked out to read";
	
	public function getString(IUser $user = NULL)
	{
		if($user == NULL)
		{
			if($this->econtentRecordDetails->isCheckOutAvailable())
			{
				return $this->getMsgAvailableOnline();
			}
			return self::getMsgCheckedOut();
		}
		else
		{
			$patronHasCheckedItOut = $this->econtentRecordDetails->isCheckedOutByPatron($user);
			if($patronHasCheckedItOut)
			{
				return $this->getMsgCheckedOutToYou();
			}
		
			$canBeCheckOut = $this->econtentRecordDetails->isCheckOutAvailable();
			if($canBeCheckOut)
			{
				return $this->getMsgAvailableOnline();
			}
			
			return self::getMsgCheckedOut();
			
		}
	}
	
	public function getUsageNotesMessage()
	{
		$message = $this->econtentRecordDetails->getUsageNotesMessage();
		if($message === false)
		{
			return self::usageNote;
		}
		return $message;
	}
	
	//PRIVATES
	private function getMsgAvailableOnline()
	{
		$msg = $this->econtentRecordDetails->getMsgAvailable();
		if ( $msg === false)
		{
			return self::availableOnline;
		}
		return $msg;
	}
	
	private function getMsgCheckedOut()
	{
		$msg = $this->econtentRecordDetails->getMsgCheckedOut();
		if ( $msg === false)
		{
			return self::checkedOut;
		}
		return $msg;
	}
	
	private function getMsgCheckedOutToYou()
	{
		
		$msg = $this->econtentRecordDetails->getMsgCheckedOutToYou();
		if ( $msg === false)
		{
			return self::checkedOutToYou;
		}
		return $msg;
	}
		
}
?>