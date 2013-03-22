<?php
require_once dirname(__FILE__).'/../../services/RecordServices.php';
require_once dirname(__FILE__).'/DTO/RecordDTO.php';

class ServerAPIItemServices
{
	private $recordServices;
	private $recordDTO;
	
	public function __construct(IRecordServices $recordServices = NULL,
								IRecordDTO $recordDTO = NULL)
	{
		if(!$recordServices) $recordServices = new RecordServices();
		$this->recordServices = $recordServices;
		
		if(!$recordDTO) $recordDTO = new RecordDTO();
		$this->recordDTO = $recordDTO;
		
	}

	public function getItemDetails($id)
	{		
		$record = $this->recordServices->getItem($id);
		if($record !== BaseDAO::noResult())
		{
			return $this->recordDTO->getDTO($record);
		}
		return array();
	}
}
?>