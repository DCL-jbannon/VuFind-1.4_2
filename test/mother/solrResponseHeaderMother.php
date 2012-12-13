<?php

require_once dirname(__FILE__).'/../../vufind/classes/solr/SolrResponseHeader.php';

class SolrResponseHeaderMother {
	
	const status = 0;
	const QTime = 0.3;
	
	
	public function getSolrResponseHeader()
	{
		$responseHeaderStdClass = new stdClass();
		$responseHeaderStdClass->status = self::status;
		$responseHeaderStdClass->QTime = self::QTime;
		$responseHeaderStdClass->params = array();
		
		$solrResponseHeader = new SolrResponseHeader();
		$solrResponseHeader->set($responseHeaderStdClass);
		return $solrResponseHeader;
	} 
	
}

?>