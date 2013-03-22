<?php
require_once dirname(__FILE__).'/../../web/sys/eContent/EContentRecord.php';
require_once dirname(__FILE__).'/../API/OverDrive/OverDriveServicesAPI.php';
require_once dirname(__FILE__).'/../Utils/RegularExpressions.php';
require_once dirname(__FILE__).'/../interfaces/IEcontentCovers.php';

class OverDriveCovers  implements IEcontentCovers
{
	private $regularExpressions;
	private $overDriveServices;
	
	public function __construct( IOverDriveServicesAPI $overDriveServices = NULL, 
								 IRegularExpressions $regularExpressions = NULL)
	{
		global $configArray;
		if(!$overDriveServices) $overDriveServices = new OverDriveServicesAPI();
		$this->overDriveServices = $overDriveServices;
		
		if(!$regularExpressions) $regularExpressions = new RegularExpressions();
		$this->regularExpressions = $regularExpressions;
	}
	
	public function getImageUrl($id, IEContentRecord $eContentRecord = NULL)
	{
		if(!$eContentRecord) $eContentRecord = new EContentRecord();
		
		//Check if exists an eContent with this id
		$eContentRecord->id = $id;
		if(!$eContentRecord->find())
		{
			throw new DomainException("OverDriveCovers::getImageUrl The ID ".$id."  is not an eContent Record");
		}
		$eContentRecord->fetch();

		//Check if the record it is a OverDrive Record
		if ( !$eContentRecord->isOverDrive() )
		{
			throw new DomainException("OverDriveCovers::getImageUrl The EContent with ID ".$id." is not an OverDrive Record <".$eContentRecord->source.">");
		}
		
		//Check if the OverDrive Record has the OverDrive ID record on sourceUrl Field
		$overDriveID = $this->regularExpressions->getFieldValueFromURL($eContentRecord->sourceUrl, "ID");
		if(empty($overDriveID))
		{
			throw new DomainException("OverDriveCovers::getImageUrl The OverDrive Record with ID ".$id." has no OverDrive ID associated");
		}
		
		$result = $this->overDriveServices->getItemMetadata($overDriveID);
		return $result->images->cover->href;
	}

}

?>