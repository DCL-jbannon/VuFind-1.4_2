<?php

require_once dirname(__FILE__).'/../../vufind/classes/monitor/OverDriveMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class OverDriveMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		$this->service = new OverDriveMonitor("5d591878-2f9a-44e2-a6c7-d5655a0fc718");
	}
	
	public function tearDown()
	{
		OverDriveAPI::$accessToken = NULL;
		parent::tearDown();
	}	
}
?>