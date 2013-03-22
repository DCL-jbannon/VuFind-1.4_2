<?php
require_once dirname(__FILE__).'/../../../vufind/classes/covers/ThreeMCovers.php';

class ThreeMCoversTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $eContentRecordMock;
	
	public function setUp()
	{
		$this->eContentRecordMock = $this->getMock("IEContentRecord", array("find","fetch", "is3M", "getSourceUrl"));
		$this->service = new ThreeMCovers($this->eContentRecordMock);
		parent::setUp();		
	}
	
	/**
	* method getImageUrl 
	* when idRecordIsNotFound
	* should throw
	* @expectedException DomainException
	*/
	public function test_getImageUrl_idRecordIsNotFound_throw ()
	{
		$id = "aNonValidId";
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->will($this->returnValue(false));
		
		$this->service->getImageUrl($id, $this->eContentRecordMock);
	}	
	
	
	/**
	 * method getImageUrl
	 * when RecordIsNot3M
	 * should throw
	 * @expectedException DomainException
	 */
	public function test_getImageUrl_RecordIsNot3M_throw ()
	{
		$id = "aNonValidId";
		$this->eContentRecordMock->expects($this->once())
								->method("find")
								->will($this->returnValue(true));
		$this->eContentRecordMock->expects($this->once())
								->method("fetch");
		$this->eContentRecordMock->expects($this->once())
								 	->method("is3M")
								 	->will($this->returnValue(false));
		$this->service->getImageUrl($id, $this->eContentRecordMock);
	}
	
	
	/**
	 * method getImageUrl
	 * when called
	 * should executesCorrectly
	 */
	public function test_getImageUrl_called_executesCorrectly ()
	{
		$id="a3MRecordId";
		$expected = "http://ebook.3m.com/delivery/img?type=DOCUMENTIMAGE&documentID=".$id."&size=LARGE";
		
		$this->eContentRecordMock->expects($this->once())
								 ->method("getSourceUrl")
								 ->will($this->returnValue("http://ebook.3m.com/library/DouglasCountyLibraries-document_id-".$id));
								 		
		$this->eContentRecordMock->expects($this->once())
									->method("find")
									->will($this->returnValue(true));
		$this->eContentRecordMock->expects($this->once())
									->method("fetch");
		$this->eContentRecordMock->expects($this->once())
									->method("is3M")
									->will($this->returnValue(true));
		$actual = $this->service->getImageUrl($id, $this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
}	

?>