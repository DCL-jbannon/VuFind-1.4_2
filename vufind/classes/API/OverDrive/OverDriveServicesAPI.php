<?php
require_once dirname(__FILE__).'/OverDriveAPI.php';
require_once dirname(__FILE__).'/../../memcache/MemcacheWrapper.php';

interface IOverDriveServicesAPI{}

class OverDriveServicesAPI implements IOverDriveServicesAPI
{
	private $odapi;
	
	public function __construct(IOverDriveAPI $overDriveAPI = NULL, IMemcacheWrapper $memcacheWrapper = NULL)
	{	
		if(!$overDriveAPI) $overDriveAPI = new OverDriveAPI();
		$this->odapi = $overDriveAPI;
	}
	
	public function getItemAvailability($itemId)
	{
		return $this->getItemInfo($itemId, "getItemAvailability");
	}
	
	public function getItemMetadata($itemId)
	{
		return $this->getItemInfo($itemId, "getItemMetadata");
	}
	
	public function checkOut($username, $itemId, $formatId)
	{
		$this->_loginSS($username);
		return $this->odapi->checkOut($itemId, $formatId);
	}
	
	public function placeHold($username, $itemId, $email)
	{
		$this->_loginSS($username);
		
		$result = $this->odapi->getItemDetails($itemId);
		
		$this->odapi->placeHold($itemId, $result->formatIdHold, $email);
		return true;
	}
	
	public function cancelHold($username, $itemId)
	{
		$this->_loginSS($username);
		
		$pc = $this->odapi->getPatronCirculation();
		$formatId = "";
		foreach ($pc->Holds as $item)
		{
			if(preg_match("/".$item['ItemId']."/i", $itemId))
			{
				$formatId = $item['FormatId'];
			}
		}
		return $this->odapi->cancelHold($itemId, $formatId);
	}
	
	public function addToWishList($username, $itemId)
	{
		$this->_loginSS($username);
		return $this->odapi->addToWishList($itemId);
	}
	
	public function removeWishList($username, $itemId)
	{
		$this->_loginSS($username);
		return $this->odapi->removeWishList($itemId);
	}
	
	public function changeLendingOptions($username, $ebookDays, $audioDays, $videoDays, $disneyDays)
	{
		$this->_loginSS($username);
		return $this->odapi->changeLendingOptions($ebookDays, $audioDays, $videoDays, $disneyDays);
	}
	
	public function getLendingOptions($username)
	{
		$this->_loginSS($username);
		return $this->odapi->getLendingOptions();
	}
	
	public function getItemDetails($itemId, $username = NULL)
	{
		if(!$username)
		{
			if(OverDriveAPI::$session === NULL)
			{
				$this->odapi->getSession();
			}
		}
		else
		{
			$this->_loginSS($username);
		}
		return $this->odapi->getItemDetails($itemId);
	}
	
	public function getMultipleItemsDetail($itemsId, $username = NULL)
	{
		if(!$username)
		{
			if(OverDriveAPI::$session === NULL)
			{
				$this->odapi->getSession();
			}
		}
		else
		{
			$this->_loginSS($username);
		}
		
		return $this->odapi->getItemDetails($itemsId);
	}
	
	public function getPatronCirculation($username)
	{
		$this->_loginSS($username);
		return $this->odapi->getPatronCirculation();
	}
	
	public function chooseFormat($username, $itemId, $formatId)
	{
		$this->_loginSS($username);
		return $this->odapi->chooseFormat($itemId, $formatId);
	}
	
	//PRIVATES
	private function _loginSS($username)
	{
		if(OverDriveAPI::$session === NULL)
		{
			$this->odapi->getSession();
			$this->odapi->loginSS($username);
		}
	}
	
	private function _initAPIWrapper()
	{
		$this->odapi->login();
		$this->odapi->getInfoDCLLibrary();
	}
	
	private function getItemInfo($itemId, $methodname)
	{
		if(OverDriveAPI::$accessToken === NULL)
		{
			$this->_initAPIWrapper();
		}
		try
		{   
			$result = $this->odapi->$methodname($itemId);
		}
		catch (OverDriveTokenExpiredException $exception)
		{
			$this->_initAPIWrapper();
			$result = $this->odapi->$methodname($itemId);
		}
		return $result;
	}
}
?>