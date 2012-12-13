<?php
require_once dirname(__FILE__).'/../../web/sys/eContent/EContentRecord.php';
require_once dirname(__FILE__).'/../services/FreegalServices.php';
require_once dirname(__FILE__).'/../API/Freegal/FreegalAPIServices.php';

class FreeGalCovers
{
	private $freegalServices;
	private $freegalApiServices;
	
	public function __construct($baseUrl, $apiKey, $libraryId, $patronID,
								IFreegalServices $freeGalServices = NULL, 
								IFreegalAPIServices $freegalApiServices = NULL)
	{
			if(!$freeGalServices) $freeGalServices = new FreegalServices();
			$this->freegalServices = $freeGalServices;
			
			if(!$freegalApiServices) $freegalApiServices = new FreegalAPIServices($baseUrl, $apiKey, $libraryId, $patronID);
			$this->freegalApiServices = $freegalApiServices;
	}
	
	public function getImageUrl($id, /*Tests*/ IEContentRecord $eContentRecord = NULL)
	{
		if(!$eContentRecord) $eContentRecord = new EContentRecord();
		$eContentRecord->id = $id;
		if(!$eContentRecord->find(true))
		{
			throw new DomainException("FreeGalCovers::getCovers No eContent with id ".$id." could be found");
		}
		
		if(!$eContentRecord->isFreegal())
		{
			throw new DomainException("FreeGalCovers::getCovers The eContent with id ".$id." is not a FreeGal Record");
		}
		
		$albumId = $this->freegalServices->getAlbumId($eContentRecord);
		if($albumId === false)
		{
			throw new DomainException("FreeGalCovers::getCovers The eContent with id ".$id." is not a FreeGal Record");
		}
		
		return $this->freegalApiServices->getCoverUrlByAlbum($eContentRecord->title, $albumId, $eContentRecord->author);
		
	}
	
	
}

?>