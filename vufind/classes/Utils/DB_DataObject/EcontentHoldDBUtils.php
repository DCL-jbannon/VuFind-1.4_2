<?php
require_once dirname(__FILE__).'/BaseDB_DataObjectUtils.php';
require_once dirname(__FILE__).'/../../../web/sys/eContent/EContentHold.php';

class EcontentHoldDBUtils extends BaseDB_DataObjectUtils
{
	
	public function getClasDBName()
	{
		return "EContentHold";
	}

	public function changeRecordId($oldRecordId, $newRecordId)
	{
		$object = $this->getCleanObject();
				
		$object->setRecordId($oldRecordId);
		$object->find();
		while($object->fetch())
		{
			$object->setRecordId($newRecordId);
			$object->update();
		}
	}
	
}

?>