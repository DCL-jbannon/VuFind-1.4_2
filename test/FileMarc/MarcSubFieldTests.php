<?php
require_once dirname(__FILE__).'/../../vufind/classes/FileMarc/MarcSubField.php';
require_once dirname(__FILE__).'/../../vufind/classes/FileMarc/FileMarc.php';

class MarcSubFieldTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	
	public function setUp()
	{
		$this->service = new MarcSubField($this->getMarcRecord());
		parent::setUp();		
	}

	/**
	 * method getIndicator
	 * when subfieldDoesNotExists
	 * should returnFalse
	 */
	public function test_getIndicator_subfieldDoesNotExists_returnFalse()
	{
		$actual = $this->executeGetIndicatorCall("192", 1, 1);
		$this->assertEmpty($actual);
	}
	
	
	/**
	* method getIndicator
	* when doesNotExists
	* should returnFalse
	*/
	public function test_getIndicator_doesNotExists_returnFalse()
	{
		$actual = $this->executeGetIndicatorCall("092", 1, 1);
		$this->assertEmpty($actual);
	}
	
	/**
	 * method getIndicator
	 * when exists
	 * should returnCorrectly
	 */
	public function test_getIndicator_exists_returnCorrectly()
	{
		$expected = "0";
		$actual = $this->executeGetIndicatorCall("082", 1, 1);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getIndicator
	 * when getSecondAndExists
	 * should returnCorrectly
	 */
	public function test_getIndicator_getSecondAndExists_returnCorrectly()
	{
		$expected = "4";
		$actual = $this->executeGetIndicatorCall("082", 2, 1);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getIndicator
	 * when getSecondAndDoesNotExists
	 * should returnFalse
	 */
	public function test_getIndicator_getSecondAndDoesNotExists_returnFalse()
	{
		$actual = $this->executeGetIndicatorCall("100", 2, 1);
		$this->assertEmpty($actual);
	}
	
	/**
	* method getIndicator
	* when firstIndicatorsecondSubFieldNumber
	* should returnCorrectly
	*/
	public function test_getIndicator_firstIndicatorsecondSubFieldNumber_returnCorrectly()
	{
		$expected = "4";
		$actual = $this->executeGetIndicatorCall("600", 1, 2);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getIndicator
	 * when secondIndicatorsecondSubFieldNumber
	 * should returnCorrectly
	 */
	public function test_getIndicator_secondIndicatorsecondSubFieldNumber_returnCorrectly()
	{
		$expected = "5";
		$actual = $this->executeGetIndicatorCall("600", 2, 2);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getIndicator
	 * when indicatorsecondSubFieldDoesNotExists
	 * should returnFalse
	 * @dataProvider DP_getIndicator_indicatorsecondSubFieldDoesNotExists
	 */
	public function test_getIndicator_indicatorsecondSubFieldDoesNotExists_returnFalse($indicatorNumber)
	{
		$actual = $this->executeGetIndicatorCall("994", $indicatorNumber, 2);
		$this->assertEmpty($actual);
	}
	
	public function DP_getIndicator_indicatorsecondSubFieldDoesNotExists()
	{
		return array(
					array(1),
					array(2)
				);
	}
	
	/**
	* method getCode
	* when subFieldDoesNotExists
	* should returnFalse
	*/
	public function test_getCode_subFieldDoesNotExists_returnFalse()
	{
		$subfieldPosition = 1;
		$code = "a";
		$actual = $this->service->getCode("985", $code, $subfieldPosition);
		$this->assertEmpty($actual);
	} 
	
	
	/**
	 * method getCode
	 * when existFirstSubFieldNotSecod
	 * should returnFalse
	 */
	public function test_getCode_existFirstSubFieldNotSecod_returnFalse()
	{
		$actual = $this->service->getCode("776", "w", "2", "1");
		$this->assertEmpty($actual);
	}
		
	/**
	 * method getCode
	 * when CodeExistsOrNot
	 * should returnCorrectly
	 * @dataProvider DP_getCode_subFieldExistsFirstSubfield
	 */
	public function test_getCode_CodeExistsOrNot_returnCorrectly($subFiueldNumber,$code, $codePosition, $subfieldPosition, $expected)
	{
		$actual = $this->service->getCode($subFiueldNumber, $code, $subfieldPosition, $codePosition);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getCode_subFieldExistsFirstSubfield()
	{
		return array(
					array("776", "w", 1, 1, "(DLC) 2008905207"),
					array("776", "w", 2, 1, "(OCoLC)262293075"),
					array("776", "w", 3, 1, false),
					array("776", "d", 1, 1, "Boulder, Colo. : Eolus Press, 2008"),
					array("655", "a", 1, 2, "Downloadable CIPA ebooks.")
				);
	}
	
	
	//executions
	private function executeGetIndicatorCall($subFieldNumber, $indicatorNumber, $subfieldNumber)
	{
		return $this->service->getIndicator($subFieldNumber, $indicatorNumber, $subfieldNumber);
	}
	
	
	private function getMarcRecord()
	{
		$fileMarc = new FileMarc(dirname(__FILE__).'/../testFiles/ACSItemTest.mrc');
		return $fileMarc->next();
	}

}