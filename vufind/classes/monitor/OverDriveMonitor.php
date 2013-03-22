<?php

require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/../API/OverDrive/OverDriveServicesAPI.php';

class OverDriveMonitor extends BaseMonitor
{

	private $itemId;
	
	public function __construct($clientKey, $clientSecret, $libraryId, $itemId, $baseUrl, $theme, $libraryCardILS, $baseSecureUrl)
	{
		$this->itemId = $itemId;
		
		/*$configArray['OverDrive']['url'] = self::baseUrl;
		$configArray['OverDrive']['theme'] = self::baseUrl;
		$configArray['OverDrive']['LibraryCardILS'] = self::baseUrl;
		$configArray['OverDrive']['baseSecureUrl'] = self::baseUrl;
		
		$configArray['OverDriveAPI']['clientKey'] = self::clientKey;
		$configArray['OverDriveAPI']['clientSecret'] = self::clientSecret;
		$configArray['OverDriveAPI']['libraryId'] = self::LibraryId;*/
		
		$this->service = new OverDriveServicesAPI();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$result = $this->service->getItemAvailability($this->itemId);
		parent::afterExec();
		return ($this->itemId == $result->id);
	}
}

?>