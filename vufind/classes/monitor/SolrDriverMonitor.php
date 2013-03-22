<?php

require_once dirname(__FILE__).'/../SolrDriver.php';

class SolrDriverMonitor extends SolrDriver
{
	private function __clone(){}
	
	public static function getInstance($indexUrl, $defaultCoreName)
	{
		return new SolrDriver($indexUrl, $defaultCoreName);
	}
		
}
?>