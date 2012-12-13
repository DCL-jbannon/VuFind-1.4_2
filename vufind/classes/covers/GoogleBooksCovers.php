<?php
require_once dirname(__FILE__).'/../API/Google/GoogleBooksAPIWrapper.php';

class GoogleBooksCovers {

	private $googleAPIWrapper;
	
	public function __construct(IGoogleBooksAPIWrapper $googleAPIWrapper = NULL)
	{
		if(!$googleAPIWrapper) $googleAPIWrapper = new GoogleBooksAPIWrapper();
		$this->googleAPIWrapper = $googleAPIWrapper;
	}
	
	
	function getImageUrl($isbn, $size)
	{
		$result = $this->googleAPIWrapper->getBookInfo($isbn);
		
		if(!isset($result->$isbn))
		{
			throw new DomainException("GoogleBooksCovers::getImageUrl Book with ISBN: ".$isbn." not found");
		}
		if(!isset($result->$isbn->thumbnail_url))
		{
			throw new DomainException("GoogleBooksCovers::getImageUrl Book with ISBN: ".$isbn." not found");
		}
		
		switch ($size)
		{
			case "large":
				return str_replace("zoom=5","zoom=2",$result->$isbn->thumbnail_url);
				break;
			case "medium":
				return str_replace("zoom=5","zoom=1",$result->$isbn->thumbnail_url);
				break;
			case "small":
			default:
				return $result->$isbn->thumbnail_url;
				break;
		}
	}

}

?>