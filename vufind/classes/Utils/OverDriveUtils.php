<?php

class OverDriveUtils
{
	public static function getEcontentRecordFromOverDriveID($overDriveId, /**Test purpouse*/ IEContentRecord $econtentRecord = NULL)
	{
		if(!$econtentRecord)
		{
			$econtentRecord = new EContentRecord();
		}
	
		$econtentRecord->sourceUrl = "http://www.emedia2go.org/ContentDetails.htm?ID=".$overDriveId;
		if($econtentRecord->find(true))
		{
			return $econtentRecord;
		}
		return false;
	}
}

?>