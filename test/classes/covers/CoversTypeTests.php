<?php
require_once dirname(__FILE__).'/../../../vufind/classes/covers/CoversType.php';


class CoversTypeTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		parent::setUp();		
	}
	
	
	/**
	* method isValid
	* when calledValidCoversType
	* should returnTrue
	* @dataProvider DP_getCover
	*/
	public function test_isValid_calledValidCovers_returnTrue($coverType)
	{
		$actual = CoversType::isValid($coverType);
		$this->assertTrue($actual);
	}
	
	public function DP_getCover()
	{
		return array(
				array(CoversType::bookCover),
				array(CoversType::audioCover),
				array(CoversType::blurayCover),
				array(CoversType::dvdCover),
				array(CoversType::emediaCover),
				array(CoversType::listCover),
				array(CoversType::magazineCover),
				array(CoversType::musicCover),
				array(CoversType::otherCover)
		);
	}
	
	/**
	 * method isValid
	 * when calledNONValidCoversType
	 * should returnTrue
	 */
	public function test_isValid_calledNONValidCoversType_returnTrue()
	{
		$coverType = "aNonValidCoverType";
		$actual = CoversType::isValid($coverType);
		$this->assertFalse($actual);
	}
	
	/**
	 *  The format comes from the field 'format_category' at resource table
	* method getCoverTypeFromFormat
	* when called
	* should returnCorrectCoverType
	* @dataProvider DP_getCoverTypeFromFormat
	*/
	public function test_getCoverTypeFromFormat_called_returnCorrectCoverType($format, $expected)
	{
		$actual = CoversType::getCoverTypeFromFormat($format);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getCoverTypeFromFormat()
	{
		return array(
				array("Books",CoversType::bookCover),
				array("Audio Book",CoversType::audioCover),
				array("Blu-Ray",CoversType::blurayCover),
				array("DVD",CoversType::dvdCover),
				array("Magazine",CoversType::magazineCover),
				array("Music",CoversType::musicCover),
				array("Other",CoversType::otherCover),
				array("aDummyFormat",CoversType::otherCover),
				array(NULL,CoversType::otherCover),
				array("",CoversType::otherCover),
		);
	}
	

}


?>