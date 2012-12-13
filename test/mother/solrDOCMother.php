<?php
require_once dirname(__FILE__).'/../../vufind/classes/solr/SolrDOC.php';

class SolrDOCMother {
	
	public function getDocument($id = NULL)
	{
		$rawDoc = $this->getDOCStdClass($id);
		$solrDoc = new SolrDOC();
		$solrDoc->set($rawDoc);
		return $solrDoc;
	}
	

	public function getDOCStdClass($id = NULL)
	{
		if(!$id) $id = "123123";
		
		$docTest = new stdClass();
		$docTest->id = $id;
		$docTest->author = "aDummyAuthor";
		$docTest->description = "aDummyDescription";
		$docTest->origin = "OverDrive";
		$docTest->mysqlid = "aDummyMysqlId";
		$docTest->title = array("aDummyTitle");
		$docTest->issn = array("aDummyISSN");
		$docTest->isbn = array("aDummyISBN");
		$docTest->publishDate=array("aDummyPublishDate");
		return $docTest;
	}
	
	
}

?>