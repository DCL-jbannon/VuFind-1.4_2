<?php

require_once dirname(__FILE__).'/../Utils/DB_DataObject/EcontentRecordDBUtils.php';
require_once dirname(__FILE__).'/../Utils/DB_DataObject/EcontentHistoryDBUtils.php';
require_once dirname(__FILE__).'/../Utils/DB_DataObject/EcontentHoldDBUtils.php';
require_once dirname(__FILE__).'/../Utils/DB_DataObject/EcontentCheckOutDBUtils.php';
require_once dirname(__FILE__).'/../Utils/DB_DataObject/ResourceDBUtils.php';
require_once dirname(__FILE__).'/../Utils/DB_DataObject/CommentsDBUtils.php';
require_once dirname(__FILE__).'/../Utils/DB_DataObject/UserRatingsDBUtils.php';


class EContentRecordServices
{
	
	public function moveEcontentRelatedInformation( $eContentRecordFromId, $eContentRecordToId,
													IDB_DataObjectUtils $econtentRecordDbUtils = NULL,
													IDB_DataObjectUtils $econtentHistoryDbUtils = NULL,
													IDB_DataObjectUtils $econtentHoldDbUtils = NULL,
													IDB_DataObjectUtils $econtentCheckOutDbUtils = NULL,
													IDB_DataObjectUtils $resourceDbUtils = NULL,
													IDB_DataObjectUtils $commentsDbUtils = NULL,
													IDB_DataObjectUtils $userRatingsDbUtils = NULL)
	{
		
		if(!$econtentRecordDbUtils) $econtentRecordDbUtils = new EcontentRecordDBUtils();
		if(!$econtentHistoryDbUtils) $econtentHistoryDbUtils = new EcontentHistoryDBUtils();
		if(!$econtentHoldDbUtils) $econtentHoldDbUtils = new EcontentHoldDBUtils();
		if(!$econtentCheckOutDbUtils) $econtentCheckOutDbUtils = new EcontentCheckOutDBUtils();
		if(!$resourceDbUtils) $resourceDbUtils = new ResourceDBUtils();
		if(!$commentsDbUtils) $commentsDbUtils = new CommentsDBUtils();
		if(!$userRatingsDbUtils) $userRatingsDbUtils = new UserRatingsDBUtils();
		
		if($econtentRecordDbUtils->getObjectById($eContentRecordFromId) === false)
		{
			throw new DomainException("There is no eContent Record with the ID: ".$eContentRecordFromId);
		}
		
		if($econtentRecordDbUtils->getObjectById($eContentRecordToId) === false)
		{
			throw new DomainException("There is no eContent Record with the ID: ".$eContentRecordToId);
		}
		
		$econtentHoldDbUtils->changeRecordId($eContentRecordFromId, $eContentRecordToId);
		$econtentHistoryDbUtils->changeRecordId($eContentRecordFromId, $eContentRecordToId);
		$econtentCheckOutDbUtils->changeRecordId($eContentRecordFromId, $eContentRecordToId);
		
		$resourceFrom = $resourceDbUtils->getByRecordId($eContentRecordFromId);
		if($resourceFrom !== false)
		{
			$resourceTo = $resourceDbUtils->getByRecordId($eContentRecordToId);
			if($resourceTo === false)
			{
				$resourceDbUtils->changeRecordIdByResourceId($resourceFrom->id, $eContentRecordToId);
			}
			else
			{
				$commentsDbUtils->changeResourceId($resourceFrom->id, $resourceTo->id);
				$userRatingsDbUtils->changeResourceId($resourceFrom->id, $resourceTo->id);
			}
		}
		
	}
	
}
?>