<?php
require_once dirname(__FILE__).'/../../../vufind/classes/covers/AttachedEcontentCovers.php';


class AttachedEcontentCoversTests extends PHPUnit_Framework_TestCase
{
	const coverFilename = "aDummyCoverAttached.png";
	private $baseDirectoryCoversTests;
	private $service;
	private $eContentMock;
	
	public function setUp()
	{
		$this->baseDirectoryCoversTests = dirname(__FILE__).'/../../testFiles/';
		
		$this->eContentMock = $this->getMock("IEContentRecord", array("find","fetch"));
		$this->service = new AttachedEcontentCovers($this->baseDirectoryCoversTests);
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
		$id="aDummyId";
		$this->eContentMock->expects($this->once())
							->method("find")
							->will($this->returnValue(false));
		$this->service->getImageUrl($id, $this->eContentMock);
	}
	
	/**
	 * method getImageUrl
	 * when coverFieldEmpty
	 * should throw
	 * @expectedException DomainException
	 */
	public function test_getImageUrl_coverFieldEmpty_throw()
	{
		$id="aDummyId";
		$this->eContentMock->cover="";
		$this->eContentMock->expects($this->once())
							->method("find")
							->will($this->returnValue(true));
		$this->eContentMock->expects($this->once())
							->method("fetch");
		$this->service->getImageUrl($id, $this->eContentMock);
	}
	
	/**
	* method getImageUrl
	* when coverDoesNotExists
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_coverDoesNotExists_throw()
	{
		$id="aDummyId";
		$this->eContentMock->cover="aNonExistingFilename";
		$this->eContentMock->expects($this->once())
							->method("find")
							->will($this->returnValue(true));
		$this->eContentMock->expects($this->once())
							->method("fetch");
		$this->service->getImageUrl($id, $this->eContentMock);
	}
	
	/**
	 * method getImageUrl
	 * when coverExists
	 * should returnCorrectPath
	 */
	public function test_getImageUrl_coverExists_returnCorrectPath()
	{
		$expected = $this->baseDirectoryCoversTests.self::coverFilename;
		$id="aDummyId";
		$this->eContentMock->cover=self::coverFilename;
		$this->eContentMock->expects($this->once())
							->method("find")
							->will($this->returnValue(true));
		$this->eContentMock->expects($this->once())
							->method("fetch");
		$actual = $this->service->getImageUrl($id, $this->eContentMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getImageUrl
	* when coverIsUrl
	* should returnUrlItself
	*/
	public function test_getImageUrl_coverIsUrl_returnUrlItself()
	{
		$expected = "http://images.contentreserve.com/ImageType-100/0017-1/%7BB65D713D-2705-4621-87FA-3E01BAB273AC%7DImg100.jpg";
		$id="aDummyId";
		$this->eContentMock->cover=$expected;
		$this->eContentMock->expects($this->once())
							->method("find")
							->will($this->returnValue(true));
		$this->eContentMock->expects($this->once())
							->method("fetch");
		$actual = $this->service->getImageUrl($id, $this->eContentMock);
		$this->assertEquals($expected, $actual);
	}
}

?>