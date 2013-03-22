<?php
require_once dirname(__FILE__).'/../../services/SearchAPIService.php';

interface IServerAPISearchServices{};

class ServerAPISearchServices implements IServerAPISearchServices
{
	private $searchAPIService;
	
	public function __construct(ISearchAPIService $searchAPIService = NULL)
	{
		if(!$searchAPIService) $searchAPIService = new SearchAPIService();
		$this->searchAPIService = $searchAPIService;
	}
	
	public function searchKeyword($lookFor, $page, $formatCategory)
	{
		return $this->searchAPIService->keywordSearch($lookFor, $page, $formatCategory);
	}
	
}
?>