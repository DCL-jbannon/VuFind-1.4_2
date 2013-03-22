<?php

require_once dirname(__FILE__).'/BaseMonitor.php';

class MemcacheMonitor extends BaseMonitor
{
	const port = 11211;
	
	public function __construct($hostname)
	{
		global $configArray;
		$this->service = new Memcache();
		$this->service->connect($hostname, self::port);
		parent::__construct();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$result = $this->service->getVersion();
		parent::afterExec();
		return (!empty($result));
	}
}

?>