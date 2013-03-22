<?php
require_once dirname(__FILE__).'/../../web/sys/serverAPI/APIStatistics.php';
require_once dirname(__FILE__).'/BaseDAO.php';

interface IAPIStatisticsDAO{}

class APIStatisticsDAO extends BaseDAO implements IAPIStatisticsDAO
{
	
	public function insertStats($clientId, $method)
	{
		$en = $this->getEntityName();
		$apis = new $en();
		$apis->setClientId($clientId);
		$apis->setMethod($method);
		$this->insert($apis);
	}
	
	public function getEntityName()
	{
		return "APIStatistics";
	}
	
}
?>