<?php
require_once dirname(__FILE__).'/ServerAPIUtils.php';
require_once dirname(__FILE__).'/ServerAPIServices.php';

require_once dirname(__FILE__).'/ServerAPIRebusListServices.php';
require_once dirname(__FILE__).'/ServerAPISearchServices.php';
require_once dirname(__FILE__).'/ServerAPIItemServices.php';

class ServerAPI
{
	private $serverAPIUtils;
	private $serverAPIServices;
	private $serverAPIRebusListServices;
	private $serverAPISearchServices;
	private $serverAPIItemsServices;
	
	public function __construct($secretKey)
	{
		$this->serverAPIUtils = new ServerAPIUtils();
		$this->serverAPIServices = new ServerAPIServices($secretKey);
		
		$this->serverAPIRebusListServices = new ServerAPIRebusListServices();
		$this->serverAPISearchServices = new ServerAPISearchServices();
		$this->serverAPIItemsServices = new ServerAPIItemServices();
	}
	
	public function run($headers, $content)
	{
		$result = "";
		try 
		{
			$methodName = $this->serverAPIUtils->getMethodHeader($headers);
			$result = $this->$methodName($headers, $content);
		}
		catch (Exception $e)
		{
			$result = $this->getErrorMessage($e);
		}
		return json_encode($result);
	}
	
	public function _auth($headers)
	{
		$authString = $this->serverAPIUtils->getAuthHeader($headers);
		$authParts = explode(";", $authString);
	
		$client = $this->serverAPIServices->getClient($authParts[0]);
		$this->saveStats($client, "auth");
		
		$accessToken = $this->serverAPIServices->loginClient($authParts[0], $authParts[1]);
		$result['accessToken'] = $accessToken;
		return $result;
	}
	
	public function _authRL($data)
	{
		if(!$this->checkRequestParameters(array('username','password'), $data))
		{
			throw new DomainException("Must receive username and password");
		}
		return $this->serverAPIRebusListServices->authRL($data->username, $data->password);
	}
	
	public function _getItemDetails($data)
	{
		if(!$this->checkRequestParameters(array('id'), $data))
		{
			throw new DomainException("Must receive Unique System ID");
		}
		return $this->serverAPIItemsServices->getItemDetails($data->id);
	}
	
	public function _searchKeyword($data)
	{
		if(!$this->checkRequestParameters(array('searchTerm','page', 'formatCategory'), $data))
		{
			throw new DomainException("Must receive searchTerm and page");
		}
		return $this->serverAPISearchServices->searchKeyword($data->searchTerm, $data->page, $data->formatCategory);
	}
	
	public function _searchKeywordRL($data)
	{
		if(!$this->checkRequestParameters(array('searchTerm','start', 'formatCategory'), $data))
		{
			throw new DomainException("Must receive searchTerm and start record value");
		}
		return $this->serverAPIRebusListServices->searchKeyword($data->searchTerm, $data->start, 20, $data->formatCategory);
	}
	
	
	//AUXILIAR METHODS
	public function __call($methodName, $arguments)
	{
		$headers = $arguments[0];
		$content = json_decode($arguments[1]);
		
		$realMethodName = "_".$methodName;
		if(!method_exists($this, $realMethodName))
		{
			throw new APIMethodNotExistsException($methodName);
		}
		
		$result = array();
		if($methodName == ServerAPIConstants::authMethod)
		{
			$result = $this->_auth($headers);
		}
		else
		{
			$accessToken = $this->serverAPIUtils->getAccessTokenHeader($headers);
			$client = $this->serverAPIServices->getClientByAccessToken($accessToken);
			$this->saveStats($client, $methodName);
			$authString = $this->serverAPIServices->validateAccessToken($accessToken);
			$result = $this->$realMethodName($content);
		}
		return $result;
	}
	
	private function saveStats($client, $method)
	{
		$this->serverAPIServices->saveStats($client->getClientId(), $method);
	}
	
	private function getErrorMessage(Exception $e)
	{
		$error = array();
		$error['errorCode'] = $e->getCode();
		$error['errorMessage'] = $e->getMessage();
		return $error;
	}
	
	/**
	 * Test integration purpouse
	 * @param unknown_type $data
	 */
	public function _dummy($content)
	{
		return array("Hello"=>"Wolrd ".$content->whoamI);
	}
	
	private function checkRequestParameters(array $requestParametersNames, $data)
	{
		if(!empty($requestParametersNames))
		{
			foreach ($requestParametersNames as $parameterName)
			{
				if(!isset($data->$parameterName))
				{
					return false;
				}
			}
		}
		return true;
	}
}
?>