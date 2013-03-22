<?php
require_once dirname(__FILE__).'/../../services/RebusListServices.php';
require_once dirname(__FILE__).'/ServerAPISearchServices.php';
require_once dirname(__FILE__).'/../../Utils/PaginationUtils.php';

class ServerAPIRebusListServices
{
	
	private $rebusListServices;
	
	public function __construct(IRebusListServices $rebusListServices = NULL)
	{
		if(!$rebusListServices) $rebusListServices = new RebusListServices;
		$this->rebusListServices = $rebusListServices;
	}
	
	public function authRL($username, $password, IRebusListServices $service = NULL)
	{
		return $this->rebusListServices->getUserInfoForRebusList($username, $password);
	}
	
	public function searchKeyword($lookFor, $start, $numRecords, $formatCategory,
																   IServerAPISearchServices $sapiss = NULL, 
																   IPaginationUtils $pagUtils = NULL)
	{
		if(!$sapiss) $sapiss = new ServerAPISearchServices();
		if(!$pagUtils) $pagUtils = new PaginationUtils();
		
		$page = $pagUtils->getNumPageByStartRecordNumberRecords($start, $numRecords);
		return $sapiss->searchKeyword($lookFor, $page, $formatCategory);
	}
	
}
?>