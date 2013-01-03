<?php
require_once dirname(__FILE__).'/../../web/sys/eContent/EContentRecord.php';

interface IThreeMUtils{}

class ThreeMUtils implements IThreeMUtils
{

	public static function get3MId(IEContentRecord $econtentRecord)
	{
		$sourceUrlParts = explode("-", $econtentRecord->sourceUrl);
		return $sourceUrlParts[count($sourceUrlParts)-1];
	}
	
	public static function getEcontentRecordFrom3MId($threemId, /**Test purpouse*/ IEContentRecord $econtentRecord = NULL)
	{
		if(!$econtentRecord)
		{
			$econtentRecord = new EContentRecord();
		}
		
		$econtentRecord->sourceUrl = "http://ebook.3m.com/library/DouglasCountyLibraries-document_id-".$threemId;
		if($econtentRecord->find(true))
		{
			return $econtentRecord;
		}
		return false;
	}
}
?>