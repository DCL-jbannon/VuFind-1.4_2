<?php
require_once dirname(__FILE__).'/../memcache/MemcacheServices.php';
require_once dirname(__FILE__).'/ThreemRecordDetails.php';
require_once dirname(__FILE__).'/OverDriveRecordDetails.php';

class EcontentDetailsFactory
{
	private function __construct(){}
	private function __clone(){}
	
	/**
	 * @param IEContentRecord $econtentRecord
	 * @return IEcontentRecordDetails|boolean
	 */
	public static function get(IEContentRecord $econtentRecord)
	{
		global $configArray;
			
		if($econtentRecord->is3M())
		{
			try
			{
				return new ThreemRecordDetails($econtentRecord);
			}
			catch(Exception $e)
			{
				return false;
			}
		}
		if($econtentRecord->isOverDrive())
		{
			return new OverDriveRecordDetails($econtentRecord); //OverDrive Screen Scraping Config Values
		}
		return false;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @param IEContentRecord $econtentRecord Optional For Test Purpouse
	 * @return IEcontentRecordDetails|boolean
	 */
	public static function getById($id, /**Test Purpouse*/ IEContentRecord $econtentRecord = NULL)
	{
		if(!$econtentRecord) $econtentRecord = new EContentRecord();
		$econtentRecord->id = $id;
		
		if ($econtentRecord->find(true))
		{
			return self::get($econtentRecord);
		}
		return false;
	}
	
}

?>