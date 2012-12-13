<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/DateTimeUtils.php';

class DateTimeUtilsTests extends PHPUnit_Framework_TestCase
{
	
	/**
	* method getTSBookCover
	* when called
	* should returnCorrectTS
	* @dataProvider DP_getTSBookCover
	*/
	public function test_getTSBookCover_called_returnCorrectTS($timestamp, $expected)
	{
		$actual = DateTimeUtils::getTSBookCover($timestamp);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getTSBookCover()
	{
		return array(
														// We want four week with the same ts. 
				  		array("1352477204", "112012"), // 09-11-2012
						array("1353082004", "112012"), // 16-11-2012
						array("1354291604", "112012"), // 30-11-2012
						array("1354896404", "122012")  // 07-12-2012
						 
					);
	}
	

}

?>