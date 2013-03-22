<?php
/**
 * Table Definition for api_clients
 */
require_once 'DB/DataObject.php';
require_once 'DB/DataObject/Cast.php';

class APIClients extends DB_DataObject 
{
	public $__table = 'api_clients';   // table name
	public $id;
	public $clientKey;
	public $clientAuthCode;
	public $clientName;
	
	/* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('api_clients',$k,$v); }
 	
 	//Setters & Getters
 	public function setClientKey($key)
 	{
 		$this->clientKey = $key;
 	}
 	
 	public function getClientKey()
 	{
 		return $this->clientKey;
 	}
 	
 	public function setClientName($name)
 	{
 		$this->clientName = $name;
 	}
 	
 	public function getClientName()
 	{
 		return $this->clientName;
 	}
 	
 	public function setClientAuthCode($clientAuthCode)
 	{
 		$this->clientAuthCode = $clientAuthCode;
 	}
 	
 	public function getClientAuthCode()
 	{
 		return $this->clientAuthCode;
 	}
 	
 	public function getClientId()
 	{
 		return $this->id;
 	}
}
?>