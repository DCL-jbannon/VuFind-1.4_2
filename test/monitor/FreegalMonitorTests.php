<?php

require_once dirname(__FILE__).'/../../vufind/classes/monitor/FreegalMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class FreegalMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		$this->service = new FreegalMonitor(  $this->config['FreeGal']['freegalUrl'], 
											  $this->config['FreeGal']['freegalAPIkey'], 
											  $this->config['FreeGal']['libraryId'],
											  $this->config['FreeGal']['patronId']);
	}
}

?>