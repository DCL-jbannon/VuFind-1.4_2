<?php
require_once dirname(__FILE__).'/BaseDB_DataObjectUtils.php';
require_once dirname(__FILE__).'/../../../web/services/MyResearch/lib/Resource.php';

class ResourceDBUtils extends BaseDB_DataObjectUtils
{
	
	public function getClasDBName()
	{
		return "Resource";
	}
	
	public function getByRecordId($recordId)
	{
		$object = $this->getCleanObject();
		$object->setRecordId($recordId);
		if(!$object->find(true))
		{
			return false;
		}
		return $object;
	}
	
	public function changeRecordIdByResourceId($resourceId, $recordId)
	{
		$object = $this->getCleanObject();
	
		$object->setResourceId($resourceId);
		$object->find(true);
		$object->setRecordId($recordId);
		$object->update();
		return $object;
	}
	
}

?>