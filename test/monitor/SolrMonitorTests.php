<?php
require_once dirname(__FILE__).'/../../vufind/classes/SolrDriver.php';
require_once dirname(__FILE__).'/../../vufind/classes/monitor/SolrMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class SolrMonitorTests extends BaseMonitorTests
{
		
	public function setUp()
	{
		parent::setUp();	
		$this->service = new SolrMonitor($this->config['Solr']['IndexUrl'], $this->config['Solr']['IndexCore']);
	}
}
?>