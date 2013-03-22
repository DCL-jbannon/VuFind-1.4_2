<?php
require_once dirname(__FILE__).'/../../vufind/classes/monitor/HIPMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class HIPMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		
		$this->service = new HIPMonitor(
										$this->config['HIP']['hipUrl'],
										$this->config['HIP']['hipProfile'], 
										$this->config['HIP']['selfRegProfile'],
										$this->config['HIP']['patronId'],
										$this->config['HIP']['pwd']);
	}
	
}