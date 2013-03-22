<?php
require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/SolrDriverMonitor.php';

class SolrMonitor extends BaseMonitor
{

	public function __construct($indexUrl, $core)
	{
		$this->service = SolrDriverMonitor::getInstance($indexUrl, $core);	
		parent::__construct();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$result = $this->service->search("*:*",0,1);
		parent::afterExec();
		
		
		return ($result->responseHeader->status == 0);
	}

}

?>