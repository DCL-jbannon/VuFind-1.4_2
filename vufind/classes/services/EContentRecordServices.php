<?php

require_once dirname(__FILE__).'/../FileMarc/FileMarc.php';
require_once dirname(__FILE__).'/../FileMarc/MarcSubField.php';

class EContentRecordServices {
	
	public function isFree(IEContentRecord $eContentRecord)
	{
		if ($eContentRecord->accessType != 'acs')
		{
			return true;
		}
		return false;
	}
	
	public function getMarcTitle(IEContentRecord $eContentRecord)
	{
		$title = $this->getFieldValue($eContentRecord, "245", "a");
		if(empty($title))
		{
			$title = $eContentRecord->title;
		}
		return $title;
	}
	
	public function getMarcAuthor(IEContentRecord $eContentRecord)
	{
		$author = $this->getFieldValue($eContentRecord, "100", "a");
		if(empty($author))
		{
			$author = $eContentRecord->author;
		}
		return $author;
	}
	
	private function getFieldValue(IEContentRecord $eContentRecord, $subFieldNumber, $code)
	{
		$marcRecord = $eContentRecord->getNormalizedMarcRecord();
		if($marcRecord)
		{
			$fileMarc = new FileMarc($marcRecord, File_Marc::SOURCE_STRING);
			$fileMarcRecord = $fileMarc->next();
			$marcSubField = new MarcSubField($fileMarcRecord);
			return $marcSubField->getCode($subFieldNumber, $code);
		}
		return "";
	}
	
}

?>