<?php

require_once dirname(__FILE__).'/FreegalAPIWrapper.php';

interface IFreegalAPIServices{}

class FreegalAPIServices implements IFreegalAPIServices{
	
	const typeSearchAlbum = "album";
	private $fapiw;
	
	public function __construct($baseUrl, $apiKey, $libraryId, $patronID, IFreegalAPIWrapper $freegalApiWrapper = NULL)
	{
		if(!$freegalApiWrapper) $freegalApiWrapper = new FreegalAPIWrapper($baseUrl, $apiKey, $libraryId, $patronID);
		$this->fapiw = $freegalApiWrapper; 
	}
	
	
	/**
	 * @param string $albumName
	 * @param integer $albumId
	 * @return String|Boolean
	 */
	public function getCoverUrlByAlbum($albumName, $albumId)
	{
		$coverUrl = false;
		$results = $this->fapiw->getSongsByTypeSearch(self::typeSearchAlbum, $albumName);
		foreach ($results as $result)
		{
			if($result->ProductID == $albumId)
			{
				$coverUrl = (string)$result->Album_Artwork;
			}
		}
		return $coverUrl;
	}
}

?>