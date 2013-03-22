<?php
require_once dirname(__FILE__).'/../../web/sys/serverAPI/APIClients.php';
require_once dirname(__FILE__).'/BaseDAO.php';

interface IAPIClientsDAO{}

class APIClientsDAO extends BaseDAO implements IAPIClientsDAO
{
	
	public function getEntityName()
	{
		return "APIClients";
	}
	
	public function getClientByclientKey($key)
	{
		$apic = new APIClients();
		$apic->setClientKey($key);
		if($apic->find(true))
		{
			$apic->fetch();
			return $apic;
		}
		return self::noResult();
	}

}
?>