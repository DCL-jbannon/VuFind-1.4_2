<?php
require_once dirname(__FILE__).'/../vufind/classes/GetMachineInfo.php';

class GetMachineInfoTest extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new GetMachineInfo();
		parent::setUp();		
	}
	
	
	/**
	* method getMachineName
	* when calles
	* should returnMachineName
	*/
	public function test_getMachineName_calles_returnMachineName()
	{
		$actual = $this->service->getMachineName();
		$this->assertNotEmpty($actual);
	}

}

?>