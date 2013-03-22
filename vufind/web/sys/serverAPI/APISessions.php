<?php
require_once 'DB/DataObject.php';
require_once 'DB/DataObject/Cast.php';

interface IAPISessions{}

class APISessions extends DB_DataObject implements IAPISessions
{
	public $__table = 'api_sessions';   // table name
	public $id;
	protected $clientId;
	protected $accessToken;
	protected $createdOn;
	
	/* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('APISessions',$k,$v); }
 	
    
    public function getCreatedOnTS()
    {
    	return strtotime($this->getCreatedOn());
    }
    
 	//Setters & Getters
 	public function setClientId($clientId)
 	{
 		$this->clientId = $clientId;
 	}
 	
 	public function getClientId()
 	{
 		return $this->clientId;
 	}
 	
 	public function setAccessToken($accessToken)
 	{
 		$this->accessToken = $accessToken;
 	}
 	
 	public function getAccessToken()
 	{
 		return $this->accessToken;
 	}
 	
 	public function getCreatedOn()
 	{
 		return $this->createdOn;
 	}	
 	
 	public function setCreatedOn($createdOn)
 	{
 		return $this->createdOn = $createdOn;
 	}
}
?>