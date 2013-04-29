<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Novelist/NovelistServices.php';
require_once dirname(__FILE__).'/../../mother/NovelistAPI/resultsMother.php';

class NovelistServicesTests extends PHPUnit_Framework_TestCase
{

	private $service;
	private $novelistWrapperMock;
	private $noveListMother;
		
	public function setUp()
	{
		$this->noveListMother = new NovelistMother();
		
		$this->novelistWrapperMock = $this->getMock("INovelistWrapper", array("getInfoByISBN"));

		$this->service = new NovelistServices($this->novelistWrapperMock);
		parent::setUp();		
	}

	/**
	 * method getGoodReadsReviewsURL
	 * when emptyFeaturedContent
	 * should returnFalse
	 */
	public function test_getGoodReadsReviewsURL_emptyFeaturedContent_returnFalse()
	{
		$expected = false;
		$result = $this->noveListMother->getResultContentByQueryEmptyFeaturedContent();
		$isbn = "aDummyISBN";
	
		$this->novelistWrapperMock->expects($this->once())
									->method("getInfoByISBN")
									->with($this->equalTo($isbn))
									->will($this->returnValue($result));
	
		$actual = $this->service->getGoodReadsReviewsURL($isbn);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method getGoodReadsReviewsURL 
	* when called
	* should executesCorrectly
	*/
	public function test_getGoodReadsReviewsURL_called_executesCorrectly()
	{
		$expected = "http://www.goodreads.com/api/reviews_widget_iframe?did=Jroe494EySm5sAvcySLHLg&header_text=&isbn=9780441008537&links=100&review_back=fff&text=000";
		$result = $this->noveListMother->getResultContentByQuery();
		$isbn = "aDummyISBN";

		$this->novelistWrapperMock->expects($this->once())
									->method("getInfoByISBN")
									->with($this->equalTo($isbn))
									->will($this->returnValue($result));
		
		$actual = $this->service->getGoodReadsReviewsURL($isbn);
		$this->assertEquals($expected, $actual);						
	}
	
	/**
	* method getGooReadsAverageRating 
	* when called
	* should executesCorrectly
	*/
	public function test_getGooReadsAverageRating_called_executesCorrectly()
	{
		$expected = "3.91";
		$result = $this->noveListMother->getResultContentByQuery();
		$isbn = "aDummyISBN";
		
		$this->novelistWrapperMock->expects($this->once())
									->method("getInfoByISBN")
									->with($this->equalTo($isbn))
									->will($this->returnValue($result));
		
		$actual = $this->service->getGooReadsAverageRating($isbn);
		$this->assertEquals($expected, $actual);
	}

}
?>