<?php
require_once dirname(__FILE__).'/../../web/services/MyResearch/lib/Resource.php';
require_once dirname(__FILE__).'/BaseDAO.php';

interface IResourceDAO{}

class ResourceDAO extends BaseDAO implements IResourceDAO
{
	const entityName = "Resource";
	
	public function getEntityName()
	{
		return self::entityName;
	}

	public function getByRecordId($recordId)
	{
		$entityName = self::entityName;
		$object = new $entityName();
		$object->record_id = $recordId;
		$result = $object->find(true);
		if($result)
		{
			return $object;
		}
		return self::noResult();
	}
	
		
	
}
?>