<?php
/**
 * Table Definition for api_clients
 */
require_once 'DB/DataObject.php';
require_once 'DB/DataObject/Cast.php';

class APIStatistics extends DB_DataObject 
{
	public $__table = 'api_statistics';   // table name
	public $id;
	public $clientId;
	public $method;
	
	/* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('api_statistics',$k,$v); }
 	
 	//Setters & Getters
 	public function setClientId($clientId)
 	{
 		$this->clientId = $clientId;
 	}
 	
 	public function getClientId()
 	{
 		return $this->clientId;
 	}
 	
 	public function setMethod($method)
 	{
 		$this->method = $method;
 	}
 	
 	public function getMethod()
 	{
 		return $this->method;
 	}
}
?>