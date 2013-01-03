<?php

require_once dirname(__FILE__).'/ThreemRecordDetails.php';

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
		if($econtentRecord->is3M())
		{
			return new ThreemRecordDetails($econtentRecord);
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