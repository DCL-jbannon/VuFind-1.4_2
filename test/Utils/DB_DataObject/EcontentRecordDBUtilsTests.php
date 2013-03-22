<?php

require_once dirname(__FILE__).'/DB_DataObjectUtilsTests.php';
require_once dirname(__FILE__).'/../../../vufind/classes/Utils/DB_DataObject/EcontentRecordDBUtils.php';

class EcontentRecordDBUtilsTests extends DB_DataObjectUtilsTests
{
	
	public function getClassDBUtilsName()
	{
		return "EcontentRecordDBUtils";
	}

	
	/**
	 * method getClassName
	 * when called
	 * should returnCorrectly
	 */
	public function test_getClassName_called_returnCorrectly()
	{
		$expected = "EContentRecord";
		$actual = $this->service->getClasDBName();
		$this->assertEquals($expected, $actual);
	}
	
}

?>