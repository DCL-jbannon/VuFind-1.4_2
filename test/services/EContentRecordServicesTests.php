<?php
require_once dirname(__FILE__).'/../../vufind/classes/services/EContentRecordServices.php';
require_once dirname(__FILE__).'/../mother/marcRecordMother.php';




class EContentRecordServicesTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $eContentRecordMock;
	private $fileMarcMock;
	private $marcRecordMother;
	
	public function setUp()
	{
		$this->marcRecordMother = new MarcRecordMother();
		$this->eContentRecordMock = $this->getMock("IEContentRecord", array("getNormalizedMarcRecord"));
		$this->service = new EContentRecordServices();
		parent::setUp();		
	}
	
	
	/**
	* method isFree
	* when recordIsNotFree
	* should returnFalse
	* @dataProvider DP_isFree_recordIsNotFree
	*/
	public function test_isFree_recordIsNotFree_returnFalse($accessType)
	{
		$this->eContentRecordMock->accessType = $accessType;
		$actual = $this->service->isFree($this->eContentRecordMock);
		$this->assertFalse($actual);
	}
	
	public function DP_isFree_recordIsNotFree()
	{
		return array(
				  		array("acs")
					);
	}
	
	/**
	* method isFree
	* when recordIsFree
	* should returnTrue
	* @dataProvider DP_isFree_recordIsFree
	*/
	public function test_isFree_recordIsFree_returnTrue($accessType)
	{
		$this->eContentRecordMock->accessType = $accessType;
		$actual = $this->service->isFree($this->eContentRecordMock);
		$this->assertTrue($actual);
	}
	
	public function DP_isFree_recordIsFree()
	{
		return array(
				array("free"),
				array("singleUse")
		);
	}
	
	
	/**
	* method getMarcTitle
	* when titleExist
	* should returnMarcTitle
	*/
	public function test_getMarcTitle_titleExist_returnMarcTitle()
	{	
		$expected = "The Anteater of Death";
		$eContentMarcRecord = $this->marcRecordMother->getEContentMarcRecord();
		$this->prepareGetTitleAuthor($eContentMarcRecord);
		$actual = $this->service->getMarcTitle($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMarcTitle
	 * when titleMarcNotExistsExist
	 * should returnBBDDTitle
	 */
	public function test_getMarcTitle_titleMarcNotExistsExist_returnBBDDTitle()
	{
		$expected = "a Dummy Title from Database";
		$eContentMarcRecord = $this->marcRecordMother->getEContentMarcRecordNoAuthorNoTitle();
		$this->prepareGetTitleAuthor($eContentMarcRecord);
		$actual = $this->service->getMarcTitle($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMarcTitle
	 * when recordHasNoMarcRecord
	 * should returnBBDDTitle
	 */
	public function test_getMarcTitle_recordHasNoMarcRecord_returnBBDDTitle()
	{
		$expected = "a Dummy Title from Database";
		$eContentMarcRecord = NULL;
		$this->prepareGetTitleAuthor($eContentMarcRecord);
		$actual = $this->service->getMarcTitle($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMarcAuthor
	 * when authorExist
	 * should returnMarcAuthor
	 */
	public function test_getMarcAuthor_authorExist_returnMarcAuthor()
	{	
		$expected = "Webb, Betty,";
		$eContentMarcRecord = $this->marcRecordMother->getEContentMarcRecord();
		$this->prepareGetTitleAuthor($eContentMarcRecord);
		$actual = $this->service->getMarcAuthor($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMarcAuthor
	 * when authorMarcNotExist
	 * should returnBBDDAuthor
	 */
	public function test_getMarcAuthor_authorMarcNotExist_returnBBDDAuthor()
	{
		$eContentMarcRecord = $this->marcRecordMother->getEContentMarcRecordNoAuthorNoTitle();
		$expected = "a Dummy Author From Database";
		$this->prepareGetTitleAuthor($eContentMarcRecord);
		$actual = $this->service->getMarcAuthor($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMarcAuthor
	 * when recordHasNoMarcRecord
	 * should returnBBDDAuthor
	 */
	public function test_getMarcAuthor_recordHasNoMarcRecord_returnBBDDAuthor()
	{
		$eContentMarcRecord = NULL;
		$expected = "a Dummy Author From Database";
		$this->prepareGetTitleAuthor($eContentMarcRecord);
		$actual = $this->service->getMarcAuthor($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	
	private function prepareGetTitleAuthor($eContentMarcRecord)
	{
		$this->eContentRecordMock->title = "a Dummy Title from Database";
		$this->eContentRecordMock->author = "a Dummy Author From Database";
		$this->eContentRecordMock->expects($this->once())
									->method("getNormalizedMarcRecord")
									->will($this->returnValue($eContentMarcRecord));
	}
	
}

?>