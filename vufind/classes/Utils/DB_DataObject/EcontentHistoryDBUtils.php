<?php
require_once dirname(__FILE__).'/BaseDB_DataObjectUtils.php';
require_once dirname(__FILE__).'/../../../web/sys/eContent/EContentHistoryEntry.php';

class EcontentHistoryDBUtils extends BaseDB_DataObjectUtils
{
	
	public function getClasDBName()
	{
		return "EContentHistoryEntry";
	}
	

	public function changeRecordId($oldRecordId, $newRecordId)
	{
		$object = $this->getCleanObject();
		$object->setRecordId($newRecordId);
		$object->whereAdd("recordId = ".$oldRecordId);
		$object->update();
	}
	
}

?>