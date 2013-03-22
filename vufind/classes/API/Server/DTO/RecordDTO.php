<?php
interface IRecordDTO{}

class RecordDTO implements IRecordDTO
{
  
  public function getDTO(IGenericRecord $record)
	{
		$dto = array();
		$dto['title'] = 			$record->getTitle();
		$dto['author'] = 			$record->getAuthor();
		$dto['secondAuthor'] = 		$record->getSecondaryAuthor();
		$dto['series'] = 			$record->getSeries();
		$dto['isbn'] = 				$record->getISBN();
		$dto['issn'] = 				$record->getISSN();
		$dto['ean'] = 				$record->getEAN();
		$dto['publicationPlace'] = 	$record->getPublicationPlace();
		$dto['publisher'] =		 	$record->getPublisher();
		$dto['year'] = 				$record->getYear();
		$dto['edition'] = 			$record->getEdition();
		$dto['url'] = 				$record->getPermanentPath();
		$dto['uniqueID'] = 			$record->getUniqueSystemID();
		return $dto;
	}	
}
?>