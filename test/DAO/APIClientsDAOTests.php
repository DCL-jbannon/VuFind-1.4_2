<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/APIClientsDAO.php';

class APIClientsDAOTests extends DAOTests
{
	
	private $clientKey = "aDummyClientKey";
	
	/**
	* method getClientByclientKey 
	* when noClientKey
	* should returnEmptyArray
	*/
	public function test_getClientByclientKey_noClientKey_returnEmptyArray()
	{
		$expected = array();
		$actual = $this->service->getClientByclientKey($this->clientKey);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getClientByclientKey
	 * when clientKey
	 * should returncorrectEntity
	 */
	public function test_getClientByclientKey_clientKey_returncorrectEntity()
	{
		$this->insertAPIClient("aClientKey");
		$this->insertAPIClient($this->clientKey);
		$actual = $this->service->getClientByclientKey($this->clientKey);
		$this->assertEquals(2, $actual->id);
	}
	
	public function getObjectToInsert()
	{
		return $this->getAPIClient();
	}
	
	public function getNameDAOClass()
	{
		return "APIClientsDAO";
	}
	
	public function getEntityClassName()
	{
		return "APIClients";
	}
	
	public function getTablesToTruncate()
	{
		return array("api_clients");
	}
	
	//private
	private function getAPIClient($clientKey = NULL)
	{
		if(!$clientKey) $clientKey = $this->clientKey;
		
		/* @var $apic APIClients */
		$apic = new $this->entityClassName();
		$apic->setClientKey($clientKey);
		$apic->setClientName("aDummyClientName");
		$apic->setClientAuthCode("aDummyAuthCode");
		return $apic;
	}
	
	private function insertAPIClient($clientKey = NULL)
	{
		$apic = $this->getAPIClient($clientKey);
		$this->service->insert($apic);	
	}
}
?>