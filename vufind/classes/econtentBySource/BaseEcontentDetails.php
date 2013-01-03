<?php
require_once dirname(__FILE__).'/EcontentRecordStatusText.php';
require_once dirname(__FILE__).'/EcontentRecordShowButtons.php';
require_once dirname(__FILE__).'/EcontentRecordLinks.php';

abstract class BaseEcontentDetails
{
	protected $econtentRecord;
	
	public function getStatusText()
	{
		return new EcontentRecordStatusText($this);
	}
	
	public function getShowButtons()
	{
		return new EcontentRecordShowButtons($this);
	}
	
	public function getLinksInfo()
	{
		return new EcontentRecordLinks($this);
	}
	
	public function getRecordId()
	{
		return $this->econtentRecord->id;
	}
	
	/**
	 * By Default the Canchel Hold is a VuFind Url
	 * @param integer $patronId
	 */
	public function getCancelHoldUrls($patronId  = NULL)
	{
		return "/EcontentRecord/".$this->getRecordId()."/CancelHold";
	}
	
	/**
	 * By Default the Check Out is a VuFind Url
	 * @param integer $patronId
	 */
	public function getCheckOutUrls($patronId  = NULL)
	{
		return "/EcontentRecord/".$this->getRecordId()."/Checkout";
	}
	
	public function getSourceName()
	{
		return $this->econtentRecord->source;
	}
	
}

?>