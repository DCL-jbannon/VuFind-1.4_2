<?php
require_once dirname(__FILE__).'/../interfaces/IEcontentCovers.php';

class AttachedEcontentCovers implements IEcontentCovers
{
	
	private $baseDirectoryCovers;
	
	public function __construct($baseDirectoryCovers)
	{
		$this->baseDirectoryCovers = $baseDirectoryCovers;
	}
	
	public function getImageUrl($id, IEContentRecord $eContentRecord = NULL)
	{
		if(!$eContentRecord) $eContentRecord = new EContentRecord();
		
		//Check if exists an eContent with this id
		$eContentRecord->id = $id;
		if(!$eContentRecord->find())
		{
			throw new DomainException("AttachedEcontentCovers::getImageUrl The ID ".$id."  is not an eContent Record");
		}
		$eContentRecord->fetch();
		
		if (empty($eContentRecord->cover))
		{
			throw new DomainException("AttachedEcontentCovers::getImageUrl The ID ".$id." has no cover");
		}
		
		if (filter_var($eContentRecord->cover, FILTER_VALIDATE_URL))
		{
			return $eContentRecord->cover;
		}
		
		$fileName = $this->baseDirectoryCovers.$eContentRecord->cover;
		
		if(!file_exists($fileName))
		{
			throw new DomainException("AttachedEcontentCovers::getImageUrl The cover ".$fileName." for the ID ".$id." does not exists");
		}
		
		return $fileName;
	}
	
}

?>