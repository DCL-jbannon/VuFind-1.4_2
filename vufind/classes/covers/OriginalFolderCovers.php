<?php
require_once dirname(__FILE__).'/../interfaces/IEcontentCovers.php';

class OriginalFolderCovers implements IEcontentCovers
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
		if(!$eContentRecord->find(true))
		{
			throw new DomainException("OriginalFolderCovers::getImageUrl The ID ".$id." is not an eContent Record");
		}
		
		$isbn = $eContentRecord->getIsbn();
		if($isbn === null)
		{
			throw new DomainException("OriginalFolderCovers::getImageUrl The eContentRecord with ID ".$id." has no ISBN defined");
		}
		
		$filename = $this->baseDirectoryCovers.$isbn.".jpg";
		if(!file_exists($filename))
		{
			throw new DomainException("OriginalFolderCovers::getImageUrl The eContentRecord with ID ".$id." has no cover on Original Folder. ".$filename);
		}
		return $filename;
	}
	
}
