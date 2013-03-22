<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/APISessionsDAO.php';

class APISessionsDAOTests extends DAOTests
{
	private $accessToken = "aDummyAccessToken";
	
	/**
	* method insertSession 
	* when called
	* should executesCorrectly
	*/
	public function test_insertSession_called_executesCorrectly()
	{
		$clientId = 123;
		$accessToken = "xtq0zdLAuwjFb+WD3Owsy+s5HFmqcywJ49ibUC/wyyg=";
		$this->service->insertSession($clientId, $accessToken);
		
		$actual = $this->service->getSessionByAccessToken($accessToken);
		
		$this->assertEquals($clientId, $actual->getClientId());
		$this->assertEquals($accessToken, $actual->getAccessToken());
		$this->assertNotEquals(BaseDAO::noResult(), $actual);
	}
	
	/**
	* method getSessionByAccessToken 
	* when noValidAccessTokenFound
	* should returnEmptyArray
	*/
	public function test_getSessionByAccessTokenBy_noValidAccessTokenFound_returnEmptyArray()
	{
		$expected = array();
		$this->insertApiSessions("anothesAccessToken");
		$actual = $this->service->getSessionByAccessToken($this->accessToken);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getSessionByAccessTokenByClientId
	 * when validAccessTokenFound
	 * should returnCorrectEntity
	 */
	public function test_getSessionByAccessToken_validAccessTokenFound_returnEmptyArray()
	{
		$expected = array();
		$this->insertApiSessions("anothesAccessToken", 345);
		$this->insertApiSessions($this->accessToken, 123);
		$this->insertApiSessions($this->accessToken, 5634);
		$actual = $this->service->getSessionByAccessToken($this->accessToken);

		$this->assertEquals($this->accessToken, $actual->getAccessToken());
		$this->assertEquals(3, $actual->id);
	}
	
	public function getObjectToInsert()
	{
		$apis = $this->getApiSessionsEntity();
		return $apis;
	}
	
	public function getNameDAOClass()
	{
		return "APISessionsDAO";
	}
	
	public function getEntityClassName()
	{
		return "APISessions";
	}
	
	public function getTablesToTruncate()
	{
		return array("api_sessions");
	}
		
	//Privates
	private function getApiSessionsEntity($accessToken = NULL, $clientId = NULL)
	{
		/* @var $apis APISessions */
		if(!$accessToken) $accessToken = $this->accessToken;
		if(!$clientId) $clientId = 1;
		
		$apis = new $this->entityClassName();
		$apis->setAccessToken($accessToken);
		$apis->setClientId($clientId);
		return $apis;
	}
	
	private function insertApiSessions($accessToken = NULL, $clientId = NULL)
	{
		$apis = $this->getApiSessionsEntity($accessToken, $clientId);
		$this->service->insert($apis);
	}
}
?>