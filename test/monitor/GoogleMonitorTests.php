<?php

require_once dirname(__FILE__).'/../../vufind/classes/monitor/GoogleMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class GoogleMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		$this->service = new GoogleMonitor($this->config['Google']['imageUrl']);
	}
}
?>