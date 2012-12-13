<?php

require_once dirname(__FILE__).'/../Utils/EContentFormatType.php';
require_once dirname(__FILE__).'/../Utils/RegularExpressions.php';
require_once dirname(__FILE__).'/../FileMarc/FileMarc.php';
require_once dirname(__FILE__).'/../FileMarc/MarcSubField.php';

class OverDriveServices 
{
	private $regularExpressions;
	
	public function __construct(IRegularExpressions $regularExpressions = NULL)
	{
		if(!$regularExpressions) $regularExpressions = new RegularExpressions();
		$this->regularExpressions = $regularExpressions;
		
	}
	
	public function getOverDriveIdFromMarcRecord($stringMarcRecord, IFileMarc $fileMarc = NULL, IMarcSubfieldMock $marcSubField = NULL )
	{
		if(!$fileMarc) $fileMarc = new FileMarc($stringMarcRecord, File_Marc::SOURCE_STRING);
		$fileMarcRecord = $fileMarc->next();

		if(!$marcSubField) $marcSubField = new MarcSubField($fileMarcRecord);
		$overDriveUrl = $marcSubField->getCode("856", "u", 1, 1);
		
		$overDriveID = $this->regularExpressions->getFieldValueFromURL($overDriveUrl, "ID");
		return $overDriveID;
	}
	
	public function getFormatType(IEContentRecord $eContentRecord)
	{
		if (preg_match("/eaudio/i", $eContentRecord->genre) > 0)
		{
			return EContentFormatType::eAudio; 
		}
		
		if (preg_match("/ebooks/i", $eContentRecord->genre) > 0 )
		{
			return EContentFormatType::eBook;
		}
		
		if (preg_match("/evideo/i", $eContentRecord->genre) > 0)
		{
			return EContentFormatType::eVideo;
		}
		return EContentFormatType::unknown;		
	}

}

?>