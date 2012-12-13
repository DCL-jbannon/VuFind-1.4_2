<?php

require_once dirname(__FILE__).'/../../../vufind/classes/covers/FreeGalCovers.php';

/**
* FreeGalCovers tests
* @package covers
*/
 class FreeGalCoversTests extends PHPUnit_Framework_TestCase
{

	private $service;
	private $eContentRecordMock;
	private $freegalServicesMock;
	private $freegalApiServicesMock;
	
	public function setUp()
	{
		$this->eContentRecordMock = $this->getMock("IEContentRecord", array("find","isFreegal"));
		$this->freegalServicesMock = $this->getMock("IFreegalServices", array("getAlbumId"));
		$this->freegalApiServicesMock = $this->getMock("IFreegalAPIServices",array("getCoverUrlByAlbum"));
		
		$this->service = new FreeGalCovers("","","","", $this->freegalServicesMock, $this->freegalApiServicesMock);
	}

	/**
	* method getImageUrl 
	* when eContentNotFound
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_eContentNotFound_throw()
	{
		
		$id = "aDummyRecord";
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(false));
		
		$this->service->getImageUrl($id, $this->eContentRecordMock);
		
	}
	
	/**
	* method getImageUrl 
	* when eContentIsNotFreeGal
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_eContentIsNotFreeGal_throw()
	{
		$id = "aDummyRecord";
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(true));
		
		$this->eContentRecordMock->expects($this->once())
									->method("isFreegal")
									->will($this->returnValue(false));
		
		$this->service->getImageUrl($id, $this->eContentRecordMock);
	}
	
	
	/**
	 * method getImageUrl
	 * when noAlbumIdFound
	 * should throw
	 * @expectedException DomainException
	 */
	public function test_getImageUrl_noAlbumIdFound_throw()
	{
		$id = "aDummyRecord";
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(true));
	
		$this->eContentRecordMock->expects($this->once())
									->method("isFreegal")
									->will($this->returnValue(true));
	
		$this->freegalServicesMock->expects($this->once())
									->method("getAlbumId")
									->with($this->equalTo($this->eContentRecordMock))
									->will($this->returnValue(false));
		
		$this->service->getImageUrl($id, $this->eContentRecordMock);
	}
	
	/**
	 * method getImageUrl
	 * when called
	 * should returnFalse
	 */
	public function test_getImageUrl_called_returnFalse()
	{
		$id = "aDummyRecord";
		$albumId = "aDummyAlbumId";
		$albumName = "aDummyAlbumName";
		$expected = $imageUrl = "aDummyUrl"; //Can be false
		
		$this->eContentRecordMock->title = $albumName;
		
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(true));
	
		$this->eContentRecordMock->expects($this->once())
									->method("isFreegal")
									->will($this->returnValue(true));
	
		$this->freegalServicesMock->expects($this->once())
									->method("getAlbumId")
									->with($this->equalTo($this->eContentRecordMock))
									->will($this->returnValue($albumId));
		
		$this->freegalApiServicesMock->expects($this->once())
									 	->method("getCoverUrlByAlbum")
									 	->with($this->equalTo($albumName), $this->equalTo($albumId))
									 	->will($this->returnValue($imageUrl));
		
		$actual = $this->service->getImageUrl($id, $this->eContentRecordMock);
		
		$this->assertEquals($expected, $actual);
	}
}  


?>