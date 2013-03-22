<?php

require_once dirname(__FILE__).'/../../vufind/classes/monitor/ThreeMMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class ThreeMMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		$this->service = new ThreeMMonitor($this->config['3MAPI']['url'], 
											  $this->config['3MAPI']['libraryId'], 
											  $this->config['3MAPI']['accesKey'],
											  $this->config['3MAPI']['itemId']);
	}
}

?>