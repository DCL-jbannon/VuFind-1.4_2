<?php
require_once dirname(__FILE__).'/../DAO/EcontentRecordDAO.php';
require_once dirname(__FILE__).'/../DAO/ResourceDAO.php';

class RecordServices
{
	private $resourceDAO;
	private $econtentRecordDAO;
	
	public function __construct(IResourceDAO $resourceDAO = NULL, IEcontentRecordDAO $econtentRecordDAO = NULL)
	{
		if(!$resourceDAO) $resourceDAO = new ResourceDAO();
		$this->resourceDAO = $resourceDAO;
		
		if(!$econtentRecordDAO) $econtentRecordDAO = new EcontentRecordDAO();
		$this->econtentRecordDAO = $econtentRecordDAO;
	}
	
	/**
	 * @param string $id
	 */
	public function getItem($id)
	{
		if(preg_match("/^".EContentRecord::prefixUnique."/", $id))
		{
			$realId = str_replace(EContentRecord::prefixUnique, "", $id);
			return $this->econtentRecordDAO->getById($realId);
		}
		return $this->resourceDAO->getByRecordId($id);
	}
}
?>