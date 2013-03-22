<?php
require_once dirname(__FILE__).'/../../web/sys/eContent/EContentRecord.php';
require_once dirname(__FILE__).'/BaseDAO.php';

interface IEcontentRecordDAO{}

class EcontentRecordDAO extends BaseDAO implements IEcontentRecordDAO
{
	
	public function getEntityName()
	{
		return "EcontentRecord";
	}

}
?>