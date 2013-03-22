<?php
require_once dirname(__FILE__).'/../../web/sys/MaterialsRequest.php';
require_once dirname(__FILE__).'/BaseDAO.php';

interface IMaterialsRequestDAO{}

class MaterialsRequestDAO extends BaseDAO implements IMaterialsRequestDAO
{
	
	public function getEntityName()
	{
		return "MaterialsRequest";
	}
	
	public function countNumberOfRequestsMadeThisWeekByUser($userId)
	{
		list($start, $end) = DateTimeUtils::getWeekStartAndEnd();
		$materialsRequest = new MaterialsRequest();
		$materialsRequest->createdBy = $userId;
		$materialsRequest->whereAdd("dateCreated >= $start");
		$materialsRequest->whereAdd("dateCreated <= $end");
		return $materialsRequest->count();
	}
		
}
?>