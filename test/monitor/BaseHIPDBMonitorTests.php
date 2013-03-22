<?php
require_once dirname(__FILE__).'/../../vufind/classes/monitor/HIPDBMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

abstract class BaseHIPDBMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{
		global $configArray;
		parent::setUp();
	
		$this->service = new HIPDBMonitor(
				$this->config['HIP']['hipUrl'],
				$this->config['HIP']['hipProfile'],
				$this->config['HIP']['selfRegProfile'],
				$this->config['HIP']['patronId'],
				$this->config['HIP']['pwd']);
	}
}