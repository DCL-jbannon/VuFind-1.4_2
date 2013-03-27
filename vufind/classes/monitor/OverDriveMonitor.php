<?php

require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/../API/OverDrive/OverDriveServicesAPI.php';

class OverDriveMonitor extends BaseMonitor
{

	private $itemId;
	
	public function __construct($itemId)
	{
		global $configArray;
		
		$configArray['Caching']['memcache_host'] = 'localhost';
		$configArray['Caching']['memcache_port'] = '11211';
		$configArray['Caching']['memcache_connection_timeout'] = 1;
		
		
		$this->itemId = $itemId;	
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