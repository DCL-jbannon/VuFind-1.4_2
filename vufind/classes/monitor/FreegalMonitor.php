<?php
require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/../API/Freegal/FreegalAPIServices.php';

class FreegalMonitor extends BaseMonitor
{
	
	public function __construct($baseUrl, $apiKey, $libraryId, $patronID)
	{
		global $configArray;	
		$this->service = new FreegalAPIServices($baseUrl, $apiKey, $libraryId, $patronID);
		parent::__construct();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$result = $this->service->getCoverUrlByAlbum("Pursuit", 188895);
		parent::afterExec();
		return (preg_match("/^http:\/\/music\.libraryideas\.com\/ioda\/188895\/188895\.jpg/", $result) == 1);
	}
}
?>