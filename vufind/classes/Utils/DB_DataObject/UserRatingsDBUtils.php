<?php
require_once dirname(__FILE__).'/BaseDB_DataObjectUtils.php';
require_once dirname(__FILE__).'/../../../web/Drivers/marmot_inc/UserRating.php';

class UserRatingsDBUtils extends BaseDB_DataObjectUtils
{
	
	public function getClasDBName()
	{
		return "UserRating";
	}
	
	public function changeResourceId($oldResourceId, $newResoureId)
	{
		$object = $this->getCleanObject();
		
		$object->setResourceId($oldResourceId);
		$object->find();
		while($object->fetch())
		{
			$object->setResourceId($newResoureId);
			$object->update();
		}
	} 
	
}

?>