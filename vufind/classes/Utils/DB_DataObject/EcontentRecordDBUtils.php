<?php

require_once dirname(__FILE__).'/BaseDB_DataObjectUtils.php';
require_once dirname(__FILE__).'/../../../web/sys/eContent/EContentRecord.php';

class EcontentRecordDBUtils extends BaseDB_DataObjectUtils
{
	
	public function getClasDBName()
	{
		return "EContentRecord";
	}
	
}

?>