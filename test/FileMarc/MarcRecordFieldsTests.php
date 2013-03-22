<?php
require_once dirname(__FILE__).'/../../vufind/classes/FileMarc/MarcRecordFields.php';
require_once dirname(__FILE__).'/../mother/marcRecordMother.php';

class MarcRecordFieldsTests extends PHPUnit_Framework_TestCase
{
	private $classImplementsIMarcRecordFieldsReaderMock;
	private $marcRecordMother;
	private $marcSubFieldMock;
	private $service;
		
	public function setUp()
	{
		$this->marcSubFieldMock = $this->getMock("IMarcSubfield", array("getCode"));
		$this->classImplementsIMarcRecordFieldsReaderMock = $this->getMock("IMarcRecordFieldsReader", array("getShelfMark", "getMarcString", "getType", "getMarcRecordFieldReader"));
		
		$this->service = new MarcRecordFields($this->classImplementsIMarcRecordFieldsReaderMock, $this->marcSubFieldMock);
		parent::setUp();		
	}
	
	/**
	* method construct 
	* when marcStringIsNotValid
	* should mockMarcSubfield
	*/
	public function test_construct_marcStringIsNotValid_should()
	{
		$expected = "MarcSubFieldNonValidMarcString";;
		$this->classImplementsIMarcRecordFieldsReaderMock->expects($this->once())
														->method("getMarcString")
														->will($this->returnValue(""));
		
		$this->service = new MarcRecordFields($this->classImplementsIMarcRecordFieldsReaderMock);
		$this->service->getISSN();
		
		$actual = $this->service->getMarcSubField();
		$this->assertEquals($expected, get_class($actual));
	}	
	
	/**
	 * method getISSN
	 * when called
	 * should returnCorrectly
	 */
	public function test_getISSN_issn_returnCorrectly()
	{
		$expected = "1941-6350";
		
		$this->prepareMarcSubFieldGetCode("022","a", $expected);
		
		$actual = $this->service->getISSN();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getISBN
	 * when called
	 * should returnCorrectly
	 */
	public function test_getISBN_called_returnCorrectly()
	{
		$expected = "9781412717205";
		
		$this->prepareMarcSubFieldGetCode("020","a", $expected);
		
		$actual = $this->service->getISBN();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getTitle
	 * when called
	 * should returnCorrectly
	 */
	public function test_getTitle_called_returnCorrectly()
	{
		$expected = "Abby Cadabby fairy tale fun";
		$this->prepareMarcSubFieldGetCode("245","a", $expected);
		
		$actual = $this->service->getTitle();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getSeries
	 * when called
	 * should returnCorrectly
	 */
	public function test_getSeries_called_returnCorrectly()
	{
		$title = "First look and find";
		$expected[] = $title."_440a";
		$expected[] = $title."_440p";
		$expected[] = $title."_800a";
		$expected[] = $title."_800b";
		$expected[] = $title."_800c";
		$expected[] = $title."_800d";
		$expected[] = $title."_800f";
		$expected[] = $title."_800p";
		$expected[] = $title."_800q";
		$expected[] = $title."_800t";
		$expected[] = $title."_830a";
		$expected[] = $title."_830p";
		
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 0, "440","a", $expected[0]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 1, "440","p", $expected[1]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 2, "800","a", $expected[2]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 3, "800","b", $expected[3]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 4, "800","c", $expected[4]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 5, "800","d", $expected[5]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 6, "800","f", $expected[6]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 7, "800","p", $expected[7]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 8, "800","q", $expected[8]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls( 9, "800","t", $expected[9]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls(10, "830","a", $expected[10]);
		$this->prepareMarcSubFieldGetCodeMultipleCalls(11, "830","p", $expected[11]);
		
		$actual = $this->service->getSeries();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getPublicationPlace
	 * when called
	 * should returnCorrectly
	 */
	public function test_getPublicationPlace_called_returnCorrectly()
	{
		$expected = "Lincolnwood, Ill. :";
		$this->prepareMarcSubFieldGetCode("260","a", $expected);
		
		$actual = $this->service->getPublicationPlace();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getPublisher
	 * when called
	 * should returnCorrectly
	 */
	public function test_getPublisher_called_returnCorrectly()
	{
		$expected = "Publications International,";
		$this->prepareMarcSubFieldGetCode("260","b", $expected);
	
		$actual = $this->service->getPublisher();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getYear
	 * when called
	 * should returnCorrectly
	 */
	public function test_getYear_called_returnCorrectly()
	{
		$year = "c2009 asd";
		$expected = "2009";
		$this->prepareMarcSubFieldGetCode("260","c", $year);
	
		$actual = $this->service->getYear();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getEdition
	 * when called
	 * should returnCorrectly
	 */
	public function test_getEdition_called_returnCorrectly()
	{
		$expected = "1st ed.";
		$this->prepareMarcSubFieldGetCode("250","a", $expected);
	
		$actual = $this->service->getEdition();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getAuthor
	 * when called
	 * should returnCorrectly
	 */
	public function test_getAuthor_called_returnCorrectly()
	{
		$expected = "Durango, Julia,";
		$this->prepareMarcSubFieldGetCode("100","a", $expected);
	
		$actual = $this->service->getAuthor();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getSourceUrl 
	* when typeIsRecord
	* should returnEmptyString
	*/
	public function test_getSourcelUrl_typeIsRecord_returnEmptyString()
	{
		$expected = "";
		$this->prepareGetType(MarcRecordFields::typeRecord);
		$actual = $this->service->getSourceUrl();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getSourceUrl
	 * when typeIsEcontentRecord
	 * should returnCorrectSourceUrl
	 */
	public function test_getSourcelUrl_typeIsEcontentRecord_returnCorrectSourceUrl()
	{
		$expected = "aDummySourceUrl";
		$this->prepareGetType(MarcRecordFields::typeEcontentRecord);
		$this->prepareMarcSubFieldGetCode("856","u", $expected);

		$actual = $this->service->getSourceUrl();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getShelfMark
	 * when called
	 * should returnCorrectly
	 */
	public function test_getShelfMark_called_returnCorrectly()
	{
		$firstPartShelfMark = "aDummyFirstPart";
		$secondPartShelfMark = "aDummySecondPart";
		$expected = $firstPartShelfMark." ".$secondPartShelfMark;
		
		$this->prepareMarcSubFieldGetCodeMultipleCalls(0, "092","a", $firstPartShelfMark);
		$this->prepareMarcSubFieldGetCodeMultipleCalls(1, "092","b", $secondPartShelfMark);
	
		$actual = $this->service->getShelfMark();
		$this->assertEquals($expected, $actual);
	}
	
	//PREPARES
	private function prepareGetType($type)
	{
		$this->classImplementsIMarcRecordFieldsReaderMock->expects($this->once())
															->method("getType")
															->will($this->returnValue($type));
	}
	
	private function prepareMarcSubFieldGetCode($subfield, $code, $expected)
	{
		$this->marcSubFieldMock->expects($this->once())
								->method("getCode")
								->with($this->equalTo($subfield), $this->equalTo($code))
								->will($this->returnValue($expected));
	}
	
	private function prepareMarcSubFieldGetCodeMultipleCalls($at, $subfield, $code, $expected)
	{
		$this->marcSubFieldMock->expects($this->at($at))
								->method("getCode")
								->with($this->equalTo($subfield), $this->equalTo($code))
								->will($this->returnValue($expected));
	}
}
?>