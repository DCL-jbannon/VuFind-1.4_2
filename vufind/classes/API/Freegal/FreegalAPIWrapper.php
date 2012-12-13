<?php

interface IFreegalAPIWrapper{}

class FreegalAPIWrapper implements IFreegalAPIWrapper
{
	
	const searchUriPath = "/services/search/";
	
	private $baseUrl = "";
	private $apiKey = "";
	private $libraryId = "";
	private $patronID = "";
	private $ch;
	
	
	public function __construct($baseUrl, $apiKey, $libraryId, $patronID)
	{
		$this->baseUrl = $baseUrl;
		$this->apiKey = $apiKey;
		$this->libraryId = $libraryId;
		$this->patronID = $patronID;
	}
	
	public function getSongsByTypeSearch($typeSearch, $searchString)
	{
		$url = $this->baseUrl.self::searchUriPath.$this->apiKey."/".$this->libraryId.'/'.$this->patronID.'/'.$typeSearch.':'.urlencode($searchString);
		return $this->getResults($url);
	}
	
	
	private function getResults($url)
	{
		$this->ch = curl_init($url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HEADER, false);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		$results = curl_exec($this->ch);
		
		$contentType = curl_getinfo($this->ch, CURLINFO_CONTENT_TYPE);
		if(preg_match("/^text\/html/", $contentType)) // 404 Error
		{
			$results = "<Songs><message>No Records</message></Songs>";
		}
		return simplexml_load_string($results);
	}
	
}

?>