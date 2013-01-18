<?php
require_once dirname(__FILE__).'/../../web/sys/eContent/EContentRecord.php';
require_once dirname(__FILE__).'/../Utils/ThreeMUtils.php';
require_once dirname(__FILE__).'/../interfaces/IEcontentCovers.php';

class ThreeMCovers implements IEcontentCovers
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
		
		$ThreeMId =  ThreeMUtils::get3MId($eContentRecord);
		return str_replace("<documentID>", $ThreeMId, self::base3MImageUrl);
	}
}
?>