<?php

require_once dirname(__FILE__).'/../../vufind/classes/monitor/SyndeticsMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class SyndeticsMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		$this->service = new SyndeticsMonitor($this->config['Syndetics']['imageUrl']);
	}
}

?>