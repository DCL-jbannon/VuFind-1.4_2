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
														// We want four weeks with the same ts. 
				  		array("1352477204", "112012"), // 09-11-2012
						array("1353082004", "112012"), // 16-11-2012
						array("1354291604", "112012"), // 30-11-2012
						array("1354896404", "122012")  // 07-12-2012
						 
					);
	}
	
	/**
	 * method getWeekStartAndEnd
	 * when called
	 * should returnMondayAndSunday
	 */
	public function test_getWeekStartAndEnd_called_returnMondayAndSunday()
	{
		list($start, $end) = DateTimeUtils::getWeekStartAndEnd();
		$this->assertEquals('Monday 00:00:00', date('l H:i:s', $start), 'Start is wrong');
		$this->assertEquals('Sunday 23:59:59', date('l H:i:s', $end), 'End is wrong');
	}
	
	/**
	* method getWeekStartAndEnd 
	* when todayIsMondayAndSpringDayLightSAvings
	* should returnCorrectly
	*/
	public function test_getWeekStartAndEnd_todayIsMondayAndSpringDayLightSAvings_returnCorrectly()
	{
		$timestamp = "1362416978"; //Monday 4/3/2013
		list($start, $end) = DateTimeUtils::getWeekStartAndEnd($timestamp);
		$this->assertEquals('Monday 00:00:00 03/04/2013', date('l H:i:s m/d/Y', $start), 'Start is wrong');
		$this->assertEquals('Sunday 23:59:59 03/10/2013', date('l H:i:s m/d/Y', $end), 'End is wrong');
	}
	
	/**
	 * method getWeekStartAndEnd
	 * when todayIsFallDaylightSaving
	 * should returnCorrectly
	 */
	public function test_getWeekStartAndEnd_todayIsFallDaylightSaving_returnCorrectly()
	{
		$timestamp = "1383027903"; //Tuesday 10/29/2013
		list($start, $end) = DateTimeUtils::getWeekStartAndEnd($timestamp);
		$this->assertEquals('Monday 00:00:00 10/28/2013', date('l H:i:s m/d/Y', $start), 'Start is wrong');
		$this->assertEquals('Sunday 23:59:59 11/03/2013', date('l H:i:s m/d/Y', $end), 'End is wrong');
	}
	
	/**
	 * method getWeekStartAndEnd
	 * when called
	 * should returnArrayWith2Values
	 */
	public function test_getWeekStartAndEnd_called_returnArrayWith2Values()
	{
		$result = DateTimeUtils::getWeekStartAndEnd();
		$this->assertTrue(is_array($result), 'Must be an array');
		$this->assertEquals(2, count($result), 'There must be 2 elements in the array');
	}
	
	
}
?>