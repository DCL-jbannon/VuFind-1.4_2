<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/PaginationUtils.php';
class PaginationUtilsTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
		
	public function setUp()
	{
		$this->service = new PaginationUtils();
		parent::setUp();		
	}
	
	/**
	* method getNumPageByStartRecordNumberRecords 
	* when pageOne
	* should returnCorrectly
	* @dataProvider DP_getNumPageByStartRecordNumberRecords_pageOne
	*/
	public function test_getNumPageByStartRecordNumberRecords_pageOne_returnCorrectly($startRecord)
	{
		$expected = 1;
		$numRecordsByPage = 20;
		$actual = $this->service->getNumPageByStartRecordNumberRecords($startRecord, $numRecordsByPage);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getNumPageByStartRecordNumberRecords_pageOne()
	{
		return array(
						array("-1"),
						array("0"),
						array("1"),
						array("20")
			   );			
	}
	
	/**
	 * method getNumPageByStartRecordNumberRecords
	 * when pageBiggerThanPageOne
	 * should returnCorrectly
	 * @dataProvider DP_getNumPageByStartRecordNumberRecords_pageBiggerThanPageOne
	 */
	public function test_getNumPageByStartRecordNumberRecords_pageBiggerThanPageOne_returnCorrectly($startRecord)
	{
		$expected = 3;
		$numRecordsByPage = 20;
		$actual = $this->service->getNumPageByStartRecordNumberRecords($startRecord, $numRecordsByPage);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getNumPageByStartRecordNumberRecords_pageBiggerThanPageOne()
	{
		return array(
				array("41"),
				array("45"),
				array("60")
		);
	}
}
?>