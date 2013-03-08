<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/StringUtils.php';

class StringUtilsTests extends PHPUnit_Framework_TestCase
{
	private $service;
		
	public function setUp()
	{
		$this->service = new StringUtils();
		parent::setUp();		
	}
	
	/**
	* method convert_ascii 
	* when called
	* should executesCorrectly
	* @dataProvider DP_convert_ascii
	*/
	public function test_convert_ascii_called_executesCorrectly($inputString, $expected)
	{
		$actual = $this->service->convert_ascii($inputString);
		$this->assertEquals($expected, $actual);
	}
	
	
	public static function DP_convert_ascii() 
	{
		return array(
						array("Antoine de Saint-ExupÃ©ry", "Antoine de Saint-Exupry"),
						array("Antoine de Saint-Exupéry From Word Document", "Antoine de Saint-Exupery From Word Document"),
						array("Antoine de Saint-Exupéry","Antoine de Saint-Exupery")
					);
	}
	
}

?>