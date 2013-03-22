<?php
require_once dirname(__FILE__).'/../../vufind/web/sys/serverAPI/APIClients.php';

class APISessionsMother
{
	const clientId = "aDummyClientId";
	const accessToken = "aDummyAccessToken";
	const createdOn = "2013-02-26 13:27:41";
	
	public function getSession($clientId =  NULL, $accessToken = NULL, $createdOn = NULL)
	{
		if(!$clientId) $clientId = self::clientId;
		if(!$accessToken) $accessToken = self::accessToken;
		if(!$createdOn) $createdOn = self::createdOn;
		
		$apis = new APISessions();
		$apis->id = 234;
		$apis->setClientId($clientId);
		$apis->setAccessToken($accessToken);
		$apis->setCreatedOn($createdOn);
		return $apis;
	}
}
?>