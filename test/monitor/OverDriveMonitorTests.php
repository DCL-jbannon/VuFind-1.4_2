<?php

require_once dirname(__FILE__).'/../../vufind/classes/monitor/OverDriveMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class OverDriveMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		$this->service = new OverDriveMonitor($this->config['OverDriveAPI']['clientKey'], 
											  $this->config['OverDriveAPI']['clientSecret'], 
											  $this->config['OverDriveAPI']['libraryId'],
											  $this->config['OverDriveAPI']['itemId'],
											  $this->config['OverDrive']['url'],
											  $this->config['OverDrive']['theme'],
											  $this->config['OverDrive']['LibraryCardILS'],
											  $this->config['OverDrive']['baseSecureUrl']);
	}
	
	public function tearDown()
	{
		OverDriveAPI::$accessToken = NULL;
		parent::tearDown();
	}	
}
?>