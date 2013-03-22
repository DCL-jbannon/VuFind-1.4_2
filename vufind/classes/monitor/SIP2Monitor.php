<?php
require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/../../web/sys/SIP2.php';
require_once dirname(__FILE__).'/../../web/sys/Logger.php';

class SIP2Monitor extends BaseMonitor
{
	private $patron;
	
	public function __construct($host, $port, $username, $pwd)
	{
		parent::__construct();
		$this->service = new sip2();	
		$this->service->hostname =$host;
		$this->service->port = $port;
		$this->service->patron = $username;
		$this->service->patronpwd = $pwd;
		
		$this->service->connect();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
	
		$in = $this->service->msgPatronInformation('hold');
		$response = $this->service->get_message($in);
		parent::afterExec();
		return ( preg_match("/GIMENEZ SENDIU, JUAN BAUTISTA/", $response) == 1);
	}

}

?>