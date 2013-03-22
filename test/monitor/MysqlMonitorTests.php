<?php
require_once dirname(__FILE__).'/../../vufind/classes/monitor/MysqlMonitor.php';
require_once dirname(__FILE__).'/BaseMonitorTests.php';

class MysqlMonitorTests extends BaseMonitorTests
{
	public function setUp()
	{	
		parent::setUp();
		$this->service = new MysqlMonitor(
											$this->config['MySQL']['DBHost'],
											$this->config['MySQL']['DBName'],
											$this->config['MySQL']['DBUsr'],
											$this->config['MySQL']['DBPwd'],
											$this->config['MySQL']['SQL']
										 );
	}
}