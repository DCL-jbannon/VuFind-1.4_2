<?php

require_once dirname(__FILE__).'/OverDriveHttpResponse.php';
require_once dirname(__FILE__).'/OverDriveAPIException.php';

interface IOverDriveAPIWrapper{}

class OverDriveAPIWrapper implements IOverDriveAPIWrapper{
	
	private $userAgent = "Douglas County Libraries OverDrive API Version 1.0";
	private $ch;
	private $tokenUrl = 'https://oauth.overdrive.com/token';
	private $baseAPIUrl = 'https://api.overdrive.com';
	
	private $odhr;
	
	public function __construct(IOverDriveHttpResponse $overDriveHttpResponse = NULL)
	{
		if (!$overDriveHttpResponse) $overDriveHttpResponse = new OverDriveHttpResponse();
		$this->odhr = $overDriveHttpResponse;
	}
	
	public function login($clientKey, $clientSecret)
	{
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_POST, TRUE);
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("Authorization: Basic ".base64_encode($clientKey.":".$clientSecret)));
		curl_setopt($this->ch, CURLOPT_HEADER, FALSE);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($this->ch, CURLOPT_URL, $this->tokenUrl);
		$result = $this->exec("login");
		curl_close($this->ch);
		return $result;
	}
	
	public function getDigitalCollection($accessToken, $productsUrl, $limit = 1, $offset = 0)
	{
		$url = $productsUrl.'?limit='.$limit.'&offset='.$offset;
		$this->ch = curl_init();
		$this->setOptionsCallWithAccessToken($accessToken);
		curl_setopt($this->ch, CURLOPT_URL, $url);
		return $this->exec("getDigitalCollection");
	}
	
	public function getInfoDCLLibrary($accessToken, $libraryId)
	{
		$this->ch = curl_init();
		$url = $this->baseAPIUrl.'/v1/libraries/'.$libraryId;
		$this->setOptionsCallWithAccessToken($accessToken);
		curl_setopt($this->ch, CURLOPT_URL, $url);
		return $this->exec('getInfoDCLLibrary');
	}
	
	
	public function getItemAvailability($accessToken, $productsUrl, $itemId)
	{
		return $this->getItemAction($accessToken, $productsUrl, $itemId, "availability", "getItemAvailability");
	}
	
	
	public function getItemMetadata($accessToken, $productsUrl, $itemId)
	{
		$result = $this->getItemAction($accessToken, $productsUrl, $itemId, "metadata", "getItemMetadata");
		if($result !== false)
		{
			$result->id = strtoupper($result->id);
		}
		return $result;
	}
	
	private function getItemAction($accessToken, $productsUrl, $itemId, $action, $methodName)
	{
		global $logger;
		
		$this->ch = curl_init();
		$url = $productsUrl.'/'.$itemId.'/'.$action;
		$this->setOptionsCallWithAccessToken($accessToken);
		curl_setopt($this->ch, CURLOPT_URL, $url);
		
		try
		{
			return $this->exec($methodName);
		}
		catch(OverDriveHttpResponseException $e)
		{
			if($logger)
			{
				$logger->log("OverDriveApiWrapper::getItemAction Method:".$methodName.". Error getting the item information on the OverDrive with ID: ".$itemId, PEAR_LOG_ERR);
			}
			return false;
		}
	}
	
	/**
	 * Description: Cuer_exec, check for errors and return the string json decoced
	 * @throws OverDriveAPIException
	 */
	private function exec($method)
	{
		$result = curl_exec($this->ch);
		$reponseCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		
		$validResponseCode = $this->odhr->checkResponseCode($reponseCode);
		if (!$validResponseCode)
		{
			throw new OverDriveTokenExpiredException();
		}
		
		if($result === FALSE)
		{
			throw new OverDriveAPIException("Error OverDrive API Wrapper".$method.". Message: ".curl_error($this->ch));
		}
		
		return json_decode($result);
	}
	
	/****** Privates functions **********/
	private function setOptionsCallWithAccessToken($accessToken)
	{
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$accessToken));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->ch, CURLOPT_HEADER, FALSE);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);
	}
		
}
