<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Server/ServerAPIServices.php';
require_once dirname(__FILE__).'/../../mother/APIClientsMother.php';
require_once dirname(__FILE__).'/../../mother/APISessionsMother.php';

class ServerAPIServicesTests extends PHPUnit_Framework_TestCase
{
	private $service;
	private $apiClientDAOMock;
	private $apiSessionDAOMock;
	private $apiStatisticsDAOMock;
	private $serverAPIUtilsMock;
	private $recordServicesMock;
	private $searcServicesMock;
	
	private $recordDTOMock;
	
	private $apiClientsMother;
	private $apiSessionMother;

	private $clientKey = "aDummyClientKey";
	private $clientAuthCode = "aDummyClientAuthCode";
	private $secretKey = "aDummySecretKey";
	private $nonValidAuthString = "aDummyNonValidAuthString";
	private $validAuthString = "iQb62QccUGZP5ztmD4eYlHvQceLUAxdIXRo9GCBU0XQ=";
	private $accessToken = "aDummyAccessToken";
	private $nonValidAccessToken = "aDummyAccessToken";
		
	public function setUp()
	{
		$this->apiClientsMother = new APIClientsMother();
		$this->apiSessionMother = new APISessionsMother();
		
		$this->apiClientDAOMock = $this->getMock("IAPIClientsDAO", array("getClientByclientKey", "getById"));
		$this->apiSessionDAOMock = $this->getMock("IAPISessionsDAO", array("insertSession", "getSessionByAccessToken"));
		$this->apiStatisticsDAOMock = $this->getMock("IAPIStatisticsDAO", array("insertStats"));		
		
		$this->recordServicesMock = $this->getMock("IRecordServices", array("getItem"));
		$this->recordDTOMock = $this->getMock("IRecordDTO", array("getDTO"));
		$this->searcServicesMock = $this->getMock("ISearchAPIService", array("keywordSearch"));
		
		$this->serverAPIUtilsMock = $this->getMock("IServerAPIUtils", array("isValidAuthString", "generateAccessToken"));
		
		$this->service = new ServerAPIServices($this->secretKey, $this->apiClientDAOMock, 
																 $this->apiSessionDAOMock,
																 $this->serverAPIUtilsMock,
																 $this->apiStatisticsDAOMock);
		parent::setUp();		
	}
	
	/**
	* method loginClient 
	* when notValidClientKey
	* should throw
	* @expectedException LoginNotValidException
	*/
	public function test_loginClient_notValid_throw()
	{
		$this->prepareAPIClientDAO(BaseDAO::noResult());
		
		$this->service->loginClient($this->clientKey, "aDummyAuthString");
	}
	
	/**
	* method loginClient 
	* when authStringNotValid
	* should throw
	* @expectedException LoginNotValidException
	*/
	public function test_loginClient_authStringNotValid_throw()
	{
		$apiClient = $this->apiClientsMother->getClient($this->clientKey, $this->clientAuthCode);
		
		$this->prepareAPIClientDAO($apiClient);
		$this->prepareServerUtilIsValidAuthString($apiClient, false);
		
		$this->service->loginClient($this->clientKey, $this->nonValidAuthString);
	}
	
	/**
	 * method loginClient
	 * when called	
	 * should returnAccessToken
	 */
	public function test_loginClient_called_returnAccessToken()
	{
		$expected = $this->accessToken;
		$apiClient = $this->apiClientsMother->getClient($this->clientKey, $this->clientAuthCode);
		
		$this->prepareAPIClientDAO($apiClient);
		$this->prepareServerUtilIsValidAuthString($apiClient, true);
		
		$this->serverAPIUtilsMock->expects($this->once())
									->method("generateAccessToken")
									->with($this->equalTo($this->secretKey))
									->will($this->returnValue($this->accessToken));
		
		$this->apiSessionDAOMock->expects($this->once())
								->method("insertSession")
								->with($this->equalTo($apiClient->id), $this->equalTo($this->accessToken));
		
		$actual = $this->service->loginClient($this->clientKey, $this->nonValidAuthString);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method validateAccessToken 
	* when notValid
	* should throw
	* @expectedException AccessTokenNotValidException
	*/
	public function test_validateAccessToken_notValid_throw()
	{
		$this->prepareAPISessionDAO($this->nonValidAccessToken, BaseDAO::noResult());
		$this->service->validateAccessToken($this->nonValidAccessToken);
	}
	
	/**
	* method validateAccessToken 
	* when accessTokenHasExpired
	* should throw
	*  @expectedException AccessTokenExpiredException
	*/
	public function test_validateAccessToken_accessTokenHasExpired_throw()
	{
		$accessTokenTS = mktime() - ServerAPIConstants::accessTokenLifetime - 1;
		$formatedDate = date("Y-m-d H:i:s", $accessTokenTS);
		$session = $this->apiSessionMother->getSession(1, $this->accessToken, $formatedDate);
		$this->prepareAPISessionDAO($this->accessToken, $session);
		
		$this->service->validateAccessToken($this->accessToken);
	}
	
	/**
	 * method validateAccessToken
	 * when accessTokenHasNotExpired
	 * should returnTrue
	 */
	public function test_validateAccessToken_accessTokenHasNotExpired_returnTrue()
	{
		$formatedDate = date("Y-m-d H:i:s");
		$session = $this->apiSessionMother->getSession(1, $this->accessToken, $formatedDate);
		$this->prepareAPISessionDAO($this->accessToken, $session);
	
		$actual = $this->service->validateAccessToken($this->accessToken);
		$this->assertTrue($actual);
	}
	
	/**
	* method saveStats 
	* when called
	* should executesCorreclty
	*/
	public function test_saveStats_called_executesCorreclty()
	{
		$clientId = "aDummyClientId";
		$methodName = "aDummyMethodAPIName";
		
		$this->apiStatisticsDAOMock->expects($this->once())
									->method("insertStats")
									->with($this->equalTo($clientId), $this->equalTo($methodName));
		$this->service->saveStats($clientId, $methodName);
	}
	
	/**
	* method getClientByAccessToken
	* when called
	* should executesCorrectly
	*/
	public function test_getClientByAccessToken_called_executesCorrectly()
	{
		$expected = "aDummyAPIClient";
		$cliendId = "aDummyClientId";
		$formatedDate = date("Y-m-d H:i:s");
		$session = $this->apiSessionMother->getSession($cliendId, $this->accessToken, $formatedDate);
		$this->prepareAPISessionDAO($this->accessToken, $session);
		
		$this->apiClientDAOMock->expects($this->once())
								->method("getById")
								->with($this->equalTo($cliendId))
								->will($this->returnValue($expected));
		
		$actual = $this->service->getClientByAccessToken($this->accessToken);
		$this->assertEquals($expected, $actual);
	}
	
		
	
		

	//prepares
	private function prepareAPISessionDAO($accessToken, $result)
	{
		$this->apiSessionDAOMock->expects($this->once())
								->method("getSessionByAccessToken")
								->with($this->equalTo($accessToken))
								->will($this->returnValue($result));
	}
	
	private function prepareAPIClientDAO($result)
	{
		$this->apiClientDAOMock->expects($this->once())
								->method("getClientByclientKey")
								->with($this->equalTo($this->clientKey))
								->will($this->returnValue($result));
	}
	
	private function prepareServerUtilIsValidAuthString($apiClient, $result)
	{
		$this->serverAPIUtilsMock->expects($this->once())
									->method("isValidAuthString")
									->with($this->equalTo($this->nonValidAuthString),
											$this->equalTo($apiClient->getClientAuthCode()),
											$this->secretKey)
									->will($this->returnValue($result));
	}
}
?>