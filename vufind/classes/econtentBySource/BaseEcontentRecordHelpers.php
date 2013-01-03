<?php


abstract class BaseEcontentRecordHelpers
{
	protected $econtentRecordDetails;
	
	public function __construct(IEcontentRecordDetails $eContentRecordDetails)
	{
		$this->econtentRecordDetails = $eContentRecordDetails;
	}
	
}


?>