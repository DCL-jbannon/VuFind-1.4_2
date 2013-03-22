<?php
require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/../API/Freegal/FreegalAPIServices.php';

class LibraryThingMonitor extends BaseMonitor
{
	
	private $imageUrl;
	
	public function __construct($imageUrl)
	{
		$this->imageUrl = $imageUrl;
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$rawImage = file_get_contents($this->imageUrl);
		$result = imagecreatefromstring($rawImage);
		parent::afterExec();
		return (is_resource($result));
	}
}
?>