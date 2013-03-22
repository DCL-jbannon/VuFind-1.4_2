<?php
require_once dirname(__FILE__).'/../../vufind/web/sys/serverAPI/APIClients.php';

class APIClientsMother
{
	const clientKey = "aDummyClientKey";
	const clientAuthCode = "aDummyClientAuthCode";
	const clientName = "aDummyClientName";
	
	public function getClient($clientKey = NULL, $clientAuthCode = NULL)
	{
		if(!$clientKey) $clientKey = self::clientKey;
		if(!$clientAuthCode) $clientAuthCode = self::clientAuthCode;
		
		$apic = new APIClients();
		$apic->id = 234;
		$apic->setClientKey($clientKey);
		$apic->setClientAuthCode($clientAuthCode);
		$apic->setClientName(self::clientName);
		return $apic;
	}
}
?>