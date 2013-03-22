<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Server/ServerAPIRebusListServices.php';

class ServerAPIRebusListServicesTests extends PHPUnit_Framework_TestCase
{
	private $service;
	private $rebusListServicesMock;
	private $paginationUtilsMock;
	private $serverAPISearchServicesMock;
		
	public function setUp()
	{
		$this->paginationUtilsMock = $this->getMock("IPaginationUtils", array("getNumPageByStartRecordNumberRecords"));
		$this->serverAPISearchServicesMock = $this->getMock("IServerAPISearchServices", array("searchKeyword"));
		
		$this->rebusListServicesMock = $this->getMock("IRebusListServices", array("getUserInfoForRebusList"));
		
		$this->service = new ServerAPIRebusListServices($this->rebusListServicesMock);
		parent::setUp();		
	}
	
	
	/**
	* method authRL 
	* when called
	* should executesCorrectly
	*/
	public function test_authRL_called_executesCorrectly()
	{
		$username = "aDummyUsername";
		$password = "aDummyPassword";
		$expected = "aDummyResult";
		
		$this->rebusListServicesMock->expects($this->once())
									->method("getUserInfoForRebusList")
									->with($this->equalTo($username), $this->equalTo($password))
									->will($this->returnValue($expected));
		
		$actual = $this->service->authRL($username, $password);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method searchKeyword
	* when called
	* should executesCorrectly
	*/
	public function test_searchKeywordRL_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$lookFor = "aDummyTermSearch";
		$page = "aDummyPage";
		$startRecord ="aDummyStartRecord";
		$numRecords = "aDummyNumRecords";
		$formatCategory = "aDummyFormatCategory";
		
		$this->paginationUtilsMock->expects($this->once())
									->method("getNumPageByStartRecordNumberRecords")
									->with($this->equalTo($startRecord), $this->equalTo($numRecords))
									->will($this->returnValue($page));
		
		$this->serverAPISearchServicesMock->expects($this->once())
											->method("searchKeyword")
											->with($this->equalTo($lookFor), $this->equalTo($page), $this->equalTo($formatCategory))
											->will($this->returnValue($expected));
		
		$actual = $this->service->searchKeyword($lookFor, $startRecord, $numRecords, $formatCategory, $this->serverAPISearchServicesMock, $this->paginationUtilsMock);
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