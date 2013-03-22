<?php
require_once dirname(__FILE__).'/BaseDB_DataObjectUtils.php';
require_once dirname(__FILE__).'/../../../web/sys/eContent/EContentHold.php';

class CommentsDBUtils extends BaseDB_DataObjectUtils
{
	
	public function getClasDBName()
	{
		return "Comments";
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