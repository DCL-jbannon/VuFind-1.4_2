<?php

require_once dirname(__FILE__).'/../../vufind/classes/solr/SolrResponse.php';
require_once dirname(__FILE__).'/solrResponseBodyMother.php';
require_once dirname(__FILE__).'/solrResponseHeaderMother.php';

class SolrResponseMother {
	
	public function getSolrReponse(ISolrResponseHeader $responseHeader = NULL, ISolrResponseBody $responseBody = NULL)
	{
		$solrResponse = new SolrResponse($responseHeader, $responseBody);
		return $solrResponse;
	}
	
}

?>