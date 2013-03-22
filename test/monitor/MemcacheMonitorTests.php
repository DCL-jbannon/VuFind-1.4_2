<?php
require_once dirname(__FILE__).'/../../vufind/classes/monitor/MemcacheMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class MemcacheMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		parent::setUp();
		$this->service = new MemcacheMonitor($this->config['Memcache']['hostname']);
	}
}