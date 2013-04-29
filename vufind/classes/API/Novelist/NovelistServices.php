<?php

require_once dirname(__FILE__).'/NovelistWrapper.php';

class NovelistServices
{
	
	private $novelistWrapper;
	
	public function __construct(INovelistWrapper $novelistWrapper = NULL)
	{
		if(!$novelistWrapper) $novelistWrapper = new NovelistWrapper();
		$this->novelistWrapper = $novelistWrapper;
	}
	
	public function getGoodReadsReviewsURL($isbn)
	{
		$result = $this->novelistWrapper->getInfoByISBN($isbn);
		if(isset( $result->FeatureContent->GoodReads))
		{
			return $result->FeatureContent->GoodReads->links[0]->url;
		}
		return false;
	}
	
	public function getGooReadsAverageRating($isbn)
	{
		$result = $this->novelistWrapper->getInfoByISBN($isbn);
		if(isset( $result->FeatureContent->GoodReads))
		{
			return $result->FeatureContent->GoodReads->average_rating;
		}
		return false;
	}
	
}
?>