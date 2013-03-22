<?php
require_once dirname(__FILE__).'/../interfaces/IMonitorTask.php';

abstract class BaseMonitor implements IMonitorTask
{
	protected $service;
	protected $timeOut = 30;
	protected $results;
	
	private $time_start;
	private $time_end;
	
	public function __construct()
	{
		register_shutdown_function(array($this, "processError"));
		ini_set('display_errors', 0);
	}
	
	public function processError()
	{
		$error = error_get_last();
		if($error !== NULL)
		{
			if($error['type'] == E_ERROR)
			{
				echo get_class($this).': TIMEOUT. PHP Message: '.$error['message'];
				exit(0);
			}
		}
	}
	
	protected function beforeExec($sleep /**Tests Purpouses **/)
	{
		//set_time_limit($this->timeOut);
		$this->time_start = microtime(true);
		
		if($sleep)
		{
			sleep($this->timeOut + 2);
		}
	}
	
	protected function afterExec()
	{
		$this->time_end = microtime(true);	
		$this->results['executionTime'] = $this->time_end - $this->time_start;
	}
	
	public function setTimeOut($timeOut)
	{
		$this->timeOut = $timeOut;
	}
	
	public function getResults()
	{
		return $this->results;
	}
	
	public function getExecutionTime()
	{
		return $this->results['executionTime'];
	}
	
}
?>