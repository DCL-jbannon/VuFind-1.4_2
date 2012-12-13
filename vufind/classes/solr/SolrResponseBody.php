<?php
require_once dirname(__FILE__).'/SolrDOC.php';

interface ISolrResponseBody{}

class SolrResponseBody implements ISolrResponseBody{
	
	private $numFound;
	private $start;
	private $docs;
	
	private $solrDOC;
	
	public function __construct(ISolrDOC $solrDOC = NULL)
	{
		if(!$solrDOC) $solrDOC = new SolrDOC();
		$this->solrDOC = $solrDOC;
	}
	
	public function set(stdClass $responseBody)
	{
		$this->setNumFound($responseBody->numFound);
		$this->setStart($responseBody->start);
		$this->setDocs($responseBody->docs);
	}
	
	private function setDocs($docs)
	{
		$newDocs = array();
		foreach($docs as $doc)
		{
			$this->solrDOC->set($doc);
			$newDocs[] = clone($this->solrDOC);
		}
		
		$this->docs = new ArrayIterator($newDocs);
	}
	
	/**
	 * @return ArrayIterator
	 */
	public function getDocs()
	{
		return $this->docs;
	}
	
	public function getNumDocs()
	{
		return $this->docs->count();
	}

	public function getStart()
	{
		return $this->start;
	}
	
	private function setStart($start)
	{
		$this->start = $start;
	}
	
	public function getNumFound()
	{
		return $this->numFound;
	}
	
	private function setNumFound($numFound)
	{
		$this->numFound = $numFound;
	}
	
}

?>