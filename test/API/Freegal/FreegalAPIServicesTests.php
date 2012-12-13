<?php
require_once dirname(__FILE__).'/../../mother/FreegalAPI/FreegalAPIResultsMother.php';
require_once dirname(__FILE__).'/../../../vufind/classes/API/Freegal/FreegalAPIServices.php';

class FreegalAPIServicesTests extends PHPUnit_Framework_TestCase
{
	const baseUrl = "aDummyUrl";
	const apiKey = "aDummyApiKey";
	const libraryId = "aDummyLibraryId";
	const patronID = "aDummyPatronID";
	
	
	private $service;
	private $freegalAPIWrapperMock;
	private $freeApiResultsMother;
	
	
	public function setUp()
	{
		$this->freeApiResultsMother = new FreegalAPIResultsMother();
		
		$this->freegalAPIWrapperMock = $this->getMock("IFreegalAPIWrapper",array("getSongsByTypeSearch"));
		$this->service = new FreegalAPIServices(self::baseUrl, self::apiKey, self::apiKey, self::patronID, $this->freegalAPIWrapperMock);
		parent::setUp();		
	}
		
	
	/**
	 * method getCoverUrlByAlbum
	 * when emptyResults
	 * should returnFalse
	 */
	public function test_getCoverUrlByAlbum_emptyResults_returnFalse()
	{
		$albumName = "aDummyAlbumName";
		$albumId = 221776;
		$result = $this->freeApiResultsMother->getEmptyResults();

		$this->exerciseGetSongsByTypeSearch($albumName, $result);;
		
		$actual = $this->service->getCoverUrlByAlbum($albumName, $albumId);
		$this->assertFalse($actual);
	}
	
	/**
	 * method getCoverUrlByAlbum
	 * when albumIdNotFound
	 * should returnFalse
	 */
	public function test_getCoverUrlByAlbum_albumIdNotFound_returnFalse()
	{
		$albumName = "aDummyAlbumName";
		$albumId = "aNonExistingAlbumId";
		$result = $this->freeApiResultsMother->getSongsSameAlbum();
		
		$this->exerciseGetSongsByTypeSearch($albumName, $result);
		
		$actual = $this->service->getCoverUrlByAlbum($albumName, $albumId);
		$this->assertFalse($actual);
	}
	
	/**
	* method getCoverUrlByAlbum
	* when albumExists
	* should returnCorrectAlbumUrl
	*/
	public function test_getCoverUrlByAlbum_albumExists_returnCorrectAlbumUrl()
	{
		$expected = "http://music.libraryideas.com/ioda/221776/221776.jpg?nvb=20121113214750&nva=20121113224750&token=54dbdc0366429ed977e27";
		$albumName = "aDummyAlbumName";
		$albumId = 221776;
		$result = $this->freeApiResultsMother->getSongsSameAlbum();
		
		$this->exerciseGetSongsByTypeSearch($albumName, $result);
		
		$actual = $this->service->getCoverUrlByAlbum($albumName, $albumId);
		$this->assertEquals($expected, $actual);
	}
	
	
	//Exercises
	private function exerciseGetSongsByTypeSearch($albumName, $result)
	{
		$this->freegalAPIWrapperMock->expects($this->once())
									->method("getSongsByTypeSearch")
									->with($this->equalTo(FreegalAPIServices::typeSearchAlbum), $this->equalTo($albumName))
									->will($this->returnValue($result));
	}
		
	
		

}


?>