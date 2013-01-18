<?php
require_once dirname(__FILE__).'/../../../vufind/classes/covers/OriginalFolderCovers.php';

class OriginalFolderCoversTests extends PHPUnit_Framework_TestCase
{
	private $service;
	private $eContentRecordMock;
	private $baseDirectoryCoversTests;
	
	public function setUp()
	{
		$this->baseDirectoryCoversTests = dirname(__FILE__).'/../../testFiles/';
		
		$this->eContentRecordMock = $this->getMock("IEContentRecord", array("find", "getIsbn"));
		$this->service = new OriginalFolderCovers($this->baseDirectoryCoversTests);
		parent::setUp();
	}
	
	
	/**
	* method getImageUrl
	* when idNotFound
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_idNotFound_throw()
	{
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(false));
		
		$id="aNonValidId";
		$this->service->getImageUrl($id, $this->eContentRecordMock);
	}
	
	/**
	* method getImageUrl 
	* when econtentHasNoISBN
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_econtentHasNoISBN_throw()
	{
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(true));
		
		$this->eContentRecordMock->expects($this->once())
									->method("getIsbn")
									->will($this->returnValue(null));
		
		$this->service->getImageUrl("aDummyId", $this->eContentRecordMock);
	}
	
	/**
	 * method getImageUrl
	 * when coverNotFoundOriginalFolder
	 * should throw
	 * @expectedException DomainException
	 */
	public function test_getImageUrl_coverNotFoundOriginalFolder_throw()
	{
		$isbn = "aDummyISBN";
		
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(true));
	
		$this->eContentRecordMock->expects($this->once())
								->method("getIsbn")
								->will($this->returnValue($isbn));
	
		$this->service->getImageUrl($isbn, $this->eContentRecordMock);
	}
	
	/**
	 * method getImageUrl
	 * when called
	 * should returnCorrectImagePath
	 */
	public function test_getImageUrl_returnCorrectImagePath_returnCorrectImagePath()
	{
		$isbn = "aDummyIsbnImageFile";
		$expected = $this->baseDirectoryCoversTests.$isbn.".jpg";
		
	
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue(true));
	
		$this->eContentRecordMock->expects($this->once())
								->method("getIsbn")
								->will($this->returnValue($isbn));
	
		$actual = $this->service->getImageUrl($isbn, $this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
}

?>