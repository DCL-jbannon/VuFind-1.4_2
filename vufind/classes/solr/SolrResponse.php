<?php
require_once dirname(__FILE__).'/SolrResponseHeader.php';
require_once dirname(__FILE__).'/SolrResponseBody.php';

interface ISolrResponse{}

class SolrResponse implements ISolrResponse{
	
	private $responseHeader;
	private $responseBody;
	
	
	public function __construct(ISolrResponseHeader $responseHeader = NULL, 
								ISolrResponseBody $responseBody = NULL)
	{
		
		if(!$responseHeader) $responseHeader = new SolrResponseHeader();
		$this->responseHeader = $responseHeader;
		
		if(!$responseBody) $responseBody = new SolrResponseBody();
		$this->responseBody = $responseBody;
		
	}
	
	public function getNextStart()
	{
		$currentStart = $this->responseHeader->getStart();
		$rows = $this->responseHeader->getRows();
		$numFound = $this->responseBody->getNumFound();
		
		
		$maxCurrentDocumentNumber = $currentStart + $rows;
		
		if($currentStart < 0)
		{
			return 0;
		}
		
		if($maxCurrentDocumentNumber >= $numFound)
		{
			return false;
		}
		else
		{
			return $currentStart + $rows;
		}
		
	}
	
	public function getPrevStart()
	{
		$currentStart = $this->responseHeader->getStart();
		$rows = $this->responseHeader->getRows();
		
		if($currentStart == 0)
		{
			return false;
		}
		if($currentStart < 0)
		{
			return 0;
		}
		if($currentStart > 0)
		{
			$prevStart = $currentStart - $rows;
			if($prevStart < 0)
			{
				$prevStart = 0;
			}
			return $prevStart;
		}
	}
	
	public function set(StdClass $response)
	{
		$this->responseHeader->set($response->responseHeader);
		$this->responseBody->set($response->response);
	}
	
	public function getDocs()
	{
		return $this->responseBody->getDocs();
	}
	
	public function getNumDocs()
	{
		return $this->responseBody->getNumDocs();
	}
	
	public function getQueryString()
	{
		return $this->responseHeader->getQueryString();
	}
	
	public function getRows()
	{
		return $this->responseHeader->getRows();
	}
	
}

?>