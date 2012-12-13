<?php

require_once dirname(__FILE__).'/../../vufind/classes/services/FreegalServices.php';

/**
* FreegalServices tests
* @package services
*/
 class FreegalServicesTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $eContentRecordMock;
	private $eContentItemMock;
	
	public function setUp()
	{
		$this->eContentRecordMock = $this->getMock("IEContentRecord", array("getItems"));
		$this->eContentItemMock = $this->getMock("EContentItem");
		$this->service = new FreegalServices();
	}
	
	
	/**
	* method getAlbumId 
	* when hasNoSongsItemsAttached
	* should returnFalse
	*/
	public function test_getAlbumId_hasNoSongsItemsAttached_returnFalse()
	{
		$this->eContentRecordMock->expects($this->once())
								->method("getItems")
								->will($this->returnValue(array()));
		
		$actual = $this->service->getAlbumId($this->eContentRecordMock);
		$this->assertFalse($actual);
	}
	
	/**
	* method getAlbumId 
	* when hasSongs
	* should returnAlbumId
	*/
	public function test_getAlbumId_hasSongs_returnAlbumId()
	{
		$expected = "171784";
		$this->eContentItemMock->link ="https://freegalmusic.com/services/login/c1b16052497962551ea7482fc86acc1ec3b39ace/11/{patronBarcode}/{patronPin}/".$expected."/TmV3IEVuZ2xhbmQ=/aW9kYQ==";
		$result[]=$this->eContentItemMock;
		
		$this->eContentRecordMock->expects($this->once())
									->method("getItems")
									->will($this->returnValue($result));
		
		$actual = $this->service->getAlbumId($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
		
	
		
	
	
}
?>