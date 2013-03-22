<?php
require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/../API/3M/ThreeMAPI.php';

class ThreeMMonitor extends BaseMonitor
{

	private $itemId;
	
	public function __construct($url, $libraryId, $accesKey, $itemId)
	{
		global $configArray;
		
		$configArray['3MAPI']['libraryId'] = $libraryId;
		$configArray['3MAPI']['url'] = $url;
		$configArray['3MAPI']['accesKey'] = $accesKey;
		
		$this->itemId = $itemId;
		$this->service = new ThreeMAPI();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$result = $this->service->getItemDetails($this->itemId);
		parent::afterExec();
		return ((string)$result->ItemId[0] == $this->itemId);
	}
	
	
	
}
?>