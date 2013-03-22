<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/RequestUtils.php';

class RequestUtilsTests extends PHPUnit_Framework_TestCase
{

	/**
	* method getRequest 
	* when doesNotExists
	* should returnEmptyString
	*/
	public function test_getRequest_doesNotExists_returnEmptyString()
	{
		$expected = "";
		$actual = RequestUtils::getRequest("aDummyNonExistingRequest");
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getRequest
	 * when exists
	 * should returnCorrectValue
	 */
	public function test_getRequest_exists_returnCorrectValue()
	{
		$expected = "aDummyValue";
		$_REQUEST['aDummyName'] = $expected;
		$actual = RequestUtils::getRequest("aDummyName");
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getGet
	 * when doesNotExists
	 * should returnEmptyString
	 */
	public function test_getGet_doesNotExists_returnEmptyString()
	{
		$expected = "";
		$actual = RequestUtils::getGet("aDummyNonExistingRequest");
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getGet
	 * when exists
	 * should returnCorrectValue
	 */
	public function test_getGet_exists_returnCorrectValue()
	{
		$expected = "aDummyValue";
		$_GET['aDummyName'] = $expected;
		$actual = RequestUtils::getGet("aDummyName");
		$this->assertEquals($expected, $actual);
	}
	
		
}



?>