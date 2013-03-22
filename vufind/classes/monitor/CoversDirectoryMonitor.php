<?php
require_once dirname(__FILE__).'/BaseMonitor.php';

class CoversDirectoryMonitor extends BaseMonitor
{
	private $coversDirectory;
	
	public function __construct($path)
	{
		$this->coversDirectory = $path;
		parent::__construct();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$itemsOnDir = scandir($this->coversDirectory);
		parent::afterExec();
		
		return ( !empty($itemsOnDir) && is_array($itemsOnDir));
	}
}
?>