<?php
require_once dirname(__FILE__).'/../../vufind/web/services/MyResearch/lib/Resource.php';


class ResourceTests extends PHPUnit_Framework_TestCase
{
	private $service;
	private $marcRecordFieldsMock;

	public function setUp()
	{
		$this->marcRecordFieldsMock = $this->getMock("IMarcRecordFields", array("getISSN", "getTitle", "getSeries",
																				"getISBN", "getPublicationPlace", "getPublisher",
																				"getYear", "getEdition", "GetEAN", "getSecondayAuthor", "getShelfMark"));
		
		$this->service = new Resource();
		parent::setUp();
	}
	
	/**
	* method getUniqueSystemID 
	* when called
	* should returnsCorrectly
	*/
	public function test_getUniqueSystemID_called_returnsCorrectly()
	{
		$expected = 12;
		$this->service->record_id = $expected;
		$actual = $this->service->getUniqueSystemID();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getPermanentPath 
	* when called
	* should returnCorrectly
	*/
	public function test_getPermanentPath_called_returnCorrectly()
	{
		$id = 12;
		$this->service->id = $id;
		$expected = "/Record/".$id;
		$actual = $this->service->getPermanentPath();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getMarcString 
	* when called
	* should returnCorrectly
	*/
	public function test_getMarcString_called_returnCorrectly()
	{
		$expected = "aDummyMarcStringValue";
		$this->service->marc = $expected;
		$actual = $this->service->getMarcString();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getType 
	* when called
	* should returnAlwaysRecord
	*/
	public function test_getType_called_returnAlwaysRecord()
	{
		$expected = "Record";
		$actual = $this->service->getType();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMarcRecordFieldReader
	 * when called
	 * should returnCorrectly
	 */
	public function test_getMarcRecordFieldReader_called_returnCorrectly()
	{
		$expected = "MarcRecordFields";
		$this->service->marc = "";
		$actual = $this->service->getMarcRecordFieldReader();
		$this->assertEquals($expected, get_class($actual));
	}
	
	
	/**
	 * method getISSN
	 * when called
	 * should executesCorrectly
	 */
	public function test_getISSN_called_executesCorrectly()
	{
		$expected = "aDummyISSN";
		$this->prepareMarcRecordFieldCallMethod("getISSN", $expected);
		
		$actual = $this->service->getISSN($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getAuthor
	 * when called
	 * should executesCorrectly
	 */
	public function test_getAuthor_called_executesCorrectly()
	{
		$expected = "aDummyAuthor";
		$this->service->author = $expected;
	
		$actual = $this->service->getAuthor();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getSecondaryAuthor
	 * when called
	 * should returnAlwaysEmptyString
	 */
	public function test_getSecondaryAuthor_called_executesCorrectly()
	{
		$expected = "";
		$actual = $this->service->getSecondaryAuthor();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getEAN
	 * when called
	 * should returnAlwaysEmptyString
	 */
	public function test_getEAN_called_executesCorrectly()
	{
		$expected = "";
		$actual = $this->service->getEAN();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getTitle
	 * when fromMysql
	 * should executesCorrectly
	 */
	public function test_getTitle_called_executesCorrectly()
	{
		$expected = "aDummyTitle";
		$this->service->setTitle($expected);
	
		$actual = $this->service->getTitle();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getTitle
	 * when mysqlTitleEmpty
	 * should executesCorrectly
	 */
	public function test_getTitle_mysqlTitleEmpty_executesCorrectly()
	{
		$expected = "aDummyTitle";
		$this->service->setTitle("");
		$this->prepareMarcRecordFieldCallMethod("getTitle", $expected);
	
		$actual = $this->service->getTitle($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getSeries
	 * when called
	 * should executesCorrectly
	 */
	public function test_getSeries_called_executesCorrectly()
	{
		$expected = array("aDummySeriesResult");
		$this->prepareMarcRecordFieldCallMethod("getSeries", $expected);
	
		$actual = $this->service->getSeries($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getISBN
	 * when called
	 * should executesCorrectly
	 */
	public function test_getISBN_called_executesCorrectly()
	{
		$expected = "aDummyISBN";
		$this->prepareMarcRecordFieldCallMethod("getISBN", $expected);
	
		$actual = $this->service->getISBN($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getPublicationPlace
	 * when called
	 * should executesCorrectly
	 */
	public function test_getPublicationPlace_called_executesCorrectly()
	{
		$expected = "aDummyPublicationPlace";
		$this->prepareMarcRecordFieldCallMethod("getPublicationPlace", $expected);
	
		$actual = $this->service->getPublicationPlace($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getPublisher
	 * when called
	 * should executesCorrectly
	 */
	public function test_getPublisher_called_executesCorrectly()
	{
		$expected = "aDummyPublisher";
		$this->prepareMarcRecordFieldCallMethod("getPublisher", $expected);
	
		$actual = $this->service->getPublisher($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getYear
	 * when called
	 * should executesCorrectly
	 */
	public function test_getYear_called_executesCorrectly()
	{
		$expected = "aDummyYear";
		$this->prepareMarcRecordFieldCallMethod("getYear", $expected);
	
		$actual = $this->service->getYear($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getEdition
	 * when called
	 * should executesCorrectly
	 */
	public function test_getEdition_called_executesCorrectly()
	{
		$expected = "aDummyEdition";
		$this->prepareMarcRecordFieldCallMethod("getEdition", $expected);
	
		$actual = $this->service->getEdition($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getShelfMark 
	* when called
	* should executesCorrectly
	*/
	public function test_getShelfMark_called_executesCorrectly()
	{
		$expected = "aDummyShelfMark";
		$this->prepareMarcRecordFieldCallMethod("getShelfMark", $expected);
		
		$actual = $this->service->getShelfMark($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
		
	
	//Privates
	private function prepareMarcRecordFieldCallMethod($method, $result)
	{
		$this->marcRecordFieldsMock->expects($this->once())
									->method($method)
									->will($this->returnValue($result));
	}
}
?>