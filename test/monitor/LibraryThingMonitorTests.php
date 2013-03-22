<?php

require_once dirname(__FILE__).'/../../vufind/classes/monitor/LibraryThingMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class LibraryThingMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		global $configArray;
		parent::setUp();
		$this->service = new LibraryThingMonitor($this->config['LibraryThing']['imageUrl']);
	}
}
?>