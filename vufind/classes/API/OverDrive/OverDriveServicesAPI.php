<?php
require_once dirname(__FILE__).'/OverDriveAPI.php';


interface IOverDriveServicesAPI{}

class OverDriveServicesAPI implements IOverDriveServicesAPI
{
	
	private $odapi;
	
	public function __construct($clientKey, $clientSecret, $libraryId, IOverDriveAPI $overDriveAPI = NULL)
	{
		if(!$overDriveAPI) $overDriveAPI = new OverDriveAPI($clientKey, $clientSecret, $libraryId);
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
	
	private function getItemInfo($itemId, $methodname)
	{
		if($this->odapi->getAccessToken() == "")
		{
			$this->_initAPI();
		}
	
		try
		{
			$result = $this->odapi->$methodname($itemId);
		}
		catch (OverDriveTokenExpiredException $exception)
		{
			$this->_initAPI();
			$result = $this->odapi->$methodname($itemId);
		}
		
		return $result;
	}
	
	private function _initAPI()
	{
		$this->odapi->login();
		$this->odapi->getInfoDCLLibrary();
	}
	
}
?>