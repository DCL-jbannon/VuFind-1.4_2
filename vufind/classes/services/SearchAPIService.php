<?php
require_once dirname(__FILE__).'/../../web/sys/SearchObject/Solr.php';
require_once dirname(__FILE__).'/../API/Server/DTO/RecordDTO.php';
require_once dirname(__FILE__).'/../DAO/ResourceDAO.php';
require_once dirname(__FILE__).'/../DAO/EcontentRecordDAO.php';

interface ISearchAPIService{}

class SearchAPIService implements ISearchAPIService
{

	private $searchSolr;
	private $recordDTO;
	private $resourceDAO;
	private $econtentRecordDAO;
	
	public function __construct(ISearchObject_Solr $searchSolr = NULL, 
								IRecordDTO $recordDTO = NULL, 
								IResourceDAO $resourceDAO = NULL,
								IEcontentRecordDAO $econtentRecordDAO = NULL)
	{
		
		if(!$searchSolr) $searchSolr = new SearchObject_Solr();
		$this->searchSolr = $searchSolr;
		
		if(!$recordDTO) $recordDTO = new RecordDTO();
		$this->recordDTO = $recordDTO;
		
		if(!$resourceDAO) $resourceDAO = new ResourceDAO();
		$this->resourceDAO = $resourceDAO;
		
		if(!$econtentRecordDAO) $econtentRecordDAO = new EcontentRecordDAO();
		$this->econtentRecordDAO = $econtentRecordDAO;
	}
	
	
	public function keywordSearch($lookfor, $page, $formatCategory)
	{
		$_REQUEST['lookfor'] = $lookfor;
		$_REQUEST['page'] = $page;
		$_REQUEST['format_category'] = $formatCategory;
		$_REQUEST['basicType'] = 'Keyword';
		$_SESSION['shards'] = array("eContent", "Main Catalog");
		
		$this->searchSolr->init('local');
		$this->searchSolr->processSearch(true, true);
		
		$result = array();
		$resultSolrSearch = $this->searchSolr->getResultRecordSet();
		foreach($resultSolrSearch as $recordSolr)
		{
			$id = $recordSolr['id'];
			if(preg_match("/^".EContentRecord::prefixUnique."/", $id))
			{
				$realId = str_replace(EContentRecord::prefixUnique, "", $id);
				$record = $this->econtentRecordDAO->getById($realId);
			}
			else
			{
				$record = $this->resourceDAO->getByRecordId($id);
			}
			$result[] = $this->recordDTO->getDTO($record);
		}
		return $result;
	}

}
?>