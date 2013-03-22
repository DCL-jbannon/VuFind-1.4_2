<?php
require_once dirname(__FILE__).'/ServerAPIException.php';
require_once dirname(__FILE__).'/ServerAPIUtils.php';
require_once dirname(__FILE__).'/../../DAO/APIClientsDAO.php';
require_once dirname(__FILE__).'/../../DAO/APISessionsDAO.php';
require_once dirname(__FILE__).'/../../DAO/APIStatisticsDAO.php';

class ServerAPIServices
{
	private $apiClientsDAO;
	private $apiSessionDAO;
	private $apiStatisticsDAO;
	private $serverAPIUtils;
	
	private $secretKey;
	
	public function __construct($secretKey, IAPIClientsDAO $apiClientsDAO = NULL,
											IAPISessionsDAO $apiSessionsDAO = NULL,
											IServerAPIUtils $serverAPIUtils = NULL,
											IAPIStatisticsDAO $apiStatisticsDAO = NULL)
	{
		if(!$apiClientsDAO) $apiClientsDAO = new APIClientsDAO();
		$this->apiClientsDAO = $apiClientsDAO;
		
		if(!$apiSessionsDAO) $apiSessionsDAO = new APISessionsDAO();
		$this->apiSessionDAO = $apiSessionsDAO;
		
		if(!$serverAPIUtils) $serverAPIUtils = new ServerAPIUtils();
		$this->serverAPIUtils = $serverAPIUtils;
		
		if(!$apiStatisticsDAO) $apiStatisticsDAO = new APIStatisticsDAO();
		$this->apiStatisticsDAO = $apiStatisticsDAO;
		
		$this->secretKey = $secretKey;
	}
	
	public function loginClient($clientKey, $authString)
	{
		$client = $this->getClient($clientKey);
		
		if(!$this->serverAPIUtils->isValidAuthString($authString, $client->getClientAuthCode(), $this->secretKey))
		{
			throw new LoginNotValidException("Auth String not valid");
		}
		
		$accessToken = $this->serverAPIUtils->generateAccessToken($this->secretKey);
		$this->apiSessionDAO->insertSession($client->id, $accessToken);
		return $accessToken;
	}
	
	public function validateAccessToken($accessToken)
	{
		$session = $this->getSessionByAccessToken($accessToken);
		
		$currentTS = mktime();
		$maxTSAllow = $session->getCreatedOnTS() + ServerAPIConstants::accessTokenLifetime;
		if($maxTSAllow < $currentTS)
		{
			throw new AccessTokenExpiredException();
		}
		return true;
	}
	
	public function getClientByAccessToken($accessToken)
	{
		$session = $this->getSessionByAccessToken($accessToken);
		return  $this->apiClientsDAO->getById($session->getClientId());
	}
	
	public function saveStats($clientId, $methodName)
	{
		$this->apiStatisticsDAO->insertStats($clientId, $methodName);
	}
	
	public function getClient($clientKey)
	{
		$client = $this->apiClientsDAO->getClientByclientKey($clientKey);
		if($client === BaseDAO::noResult())
		{
			throw new LoginNotValidException("ClientKey not valid");
		}
		return $client;
	}
	
	private function getSessionByAccessToken($accessToken)
	{
		$session = $this->apiSessionDAO->getSessionByAccessToken($accessToken);
		if($session == BaseDAO::noResult())
		{
			throw new AccessTokenNotValidException();
		}
		return $session;
	}
}
?>