<?php

abstract class BaseMonitorTests extends PHPUnit_Framework_TestCase
{
	protected $service;
	protected $config;
	
	public function setUp()
	{
		$this->config = parse_ini_file(dirname(__FILE__).'/../../sites/dcl.localhost/conf/monitor.ini', true);
		parent::setUp();
	}
	
	/**
	 * method exec
	 * when called
	 * should executesCorrectly
	 */
	public function test_exec_called_executesCorrectly()
	{
		$actual = $this->service->exec();
		$this->assertTrue($actual);
		$this->assertTrue(is_float($this->service->getExecutionTime()));
	}
	
	
	/**
	 * method exec
	 * when calledWithSleepTime
	 * should timeOutErrorThrow
	 */
	/*
	 public function test_exec_calledWithSleepTime_timeOutErrorThrow()
	 {
		$this->service->setTimeOut(1);
		$this->service->exec(true);
	}
	*/
}

?>