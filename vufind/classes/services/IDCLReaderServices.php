<?php

require_once dirname(__FILE__).'/../SolrDriver.php';
require_once dirname(__FILE__).'/../solr/SolrResponse.php';

class IDCLReaderServices {
	
	/* @var $solrDriver SolrDriver */
	private $solrDriver;
	private $solrResponse;
	
	public function __construct(ISolrDriver $solrDriver = NULL, ISolrResponse $solrResponseBody = NULL)
	{
		global $configArray;
		
		if(!$solrDriver) $solrDriver = new SolrDriver($configArray['SOLR_STORE']['url'], $configArray['SOLR_STORE']['index']);
		$this->solrDriver = $solrDriver;
		
		if(!$solrResponseBody) $solrResponseBody = new SolrResponse();
		$this->solrResponse = $solrResponseBody;
	}
	
	public function isValidEContent($id)
	{
		
		$solrResponseStdClass = $this->solrDriver->search('mysqlid:'.$id);
		
		$this->solrResponse->set($solrResponseStdClass);
		$numDocs = $this->solrResponse->getNumDocs();
		
		if($numDocs == 0)
		{
			return false;
		}
		return true;
	}
	
	
	/**
	 * @TODO Do it better
	 */
	public function isIOSPortalDeviceCompatible()
	{
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match("/iPhone/",$userAgent) > 0)
		{
			return true;
		}
		if (preg_match("/iPod/",$userAgent) > 0)
		{
			return true;
		}
		if (preg_match("/iPad/",$userAgent) > 0)
		{
			return true;
		}
		return false;
	}
	
}

?>