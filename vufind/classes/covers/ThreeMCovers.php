<?php

require_once dirname(__FILE__).'/../../web/sys/eContent/EContentRecord.php';

class ThreeMCovers
{
	const base3MImageUrl = "http://ebook.3m.com/delivery/img?type=DOCUMENTIMAGE&documentID=<documentID>&size=LARGE";
	
	public function getImageUrl($id, IEContentRecord $eContentRecord = NULL)
	{
		if(!$eContentRecord) $eContentRecord = new EContentRecord();
		
		$eContentRecord->id = $id;
		
		if (!$eContentRecord->find())
		{
			throw new DomainException("ThreeMCovers::getImageUrl The id ".$id." is not valid");
		}
		
		
		$eContentRecord->fetch();
		if(!$eContentRecord->is3M())
		{
			throw new DomainException("ThreeMCovers::getImageUrl The Record with id ".$id.",  is not a 3M record");
		}
		
		$sourceUrlParts = explode("-", $eContentRecord->sourceUrl);
		$ThreeMId =  $sourceUrlParts[count($sourceUrlParts)-1];
		return str_replace("<documentID>", $ThreeMId, self::base3MImageUrl);
	}
}
?>