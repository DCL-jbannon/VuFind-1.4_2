<?php

require_once dirname(__FILE__).'/../../vufind/classes/solr/SolrResponseBody.php';

class SolrResponseBodyMother {
	
	const numFound = 0;
	const start = 0;
	const maxScore = 1;
	
	public function getResponseBody($numFound = NULL)
	{
		if(!$numFound) $numFound = self::numFound;
		
		$responseBodyStdClass = new stdClass();
		$responseBodyStdClass->numFound = $numFound;
		$responseBodyStdClass->start = self::start;
		$responseBodyStdClass->maxScore = self::maxScore;
		
		$solrResponseBody = new SolrResponseBody();
		$solrResponseBody->set($responseBodyStdClass);
		return $solrResponseBody;
	}
	
	
}


?>