<?php
require_once dirname(__FILE__).'/../../vufind/classes/monitor/CoversDirectoryMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class CoversDirectoryMonitorTests extends BaseMonitorTests
{
	
	public function setUp()
	{
		parent::setUp();
		$this->service = new CoversDirectoryMonitor($this->config['CoversServer']['path']);
	}
	
}

?>