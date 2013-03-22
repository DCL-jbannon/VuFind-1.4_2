<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/APIStatisticsDAO.php';

class APIStatisticsDAOTests extends DAOTests
{
	
	/**
	* method insertStats 
	* when called
	* should executesCorrectly
	*/
	public function test_insertStats_called_executesCorrectly()
	{
		$clientId = 123;
		$method = "aDummyMethod";
		
		$this->service->insertStats($clientId, $method);
		
		$apis = $this->service->getById(1);
		
		$this->assertEquals(1, $apis->id);
		$this->assertEquals($clientId, $apis->getClientId());
		$this->assertEquals($method, $apis->getMethod());
	}
	
	public function getObjectToInsert()
	{
	 	$apis = new $this->entityClassName();
	 	$apis->setClientId(1);
	 	$apis->setMethod("aDummyMethod");
		return $apis;
	}
	
	public function getNameDAOClass()
	{
		return "APIStatisticsDAO";
	}
	
	public function getEntityClassName()
	{
		return "APIStatistics";
	}
	
	public function getTablesToTruncate()
	{
		return array("api_statistics");
	}
}
?>