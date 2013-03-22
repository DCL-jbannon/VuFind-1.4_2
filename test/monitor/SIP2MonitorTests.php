<?php
require_once dirname(__FILE__).'/../../vufind/classes/monitor/SIP2Monitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class SIP2MonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		
		$this->service = new SIP2Monitor($this->config['SIP2']['host'],
										 $this->config['SIP2']['port'],
										 $this->config['HIP']['patronId'],
										 $this->config['HIP']['pwd']);
	}
}