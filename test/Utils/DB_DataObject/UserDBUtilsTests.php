<?php

require_once dirname(__FILE__).'/DB_DataObjectUtilsTests.php';
require_once dirname(__FILE__).'/../../../vufind/classes/Utils/DB_DataObject/UserDBUtils.php';

class UserDBUtilsTests extends DB_DataObjectUtilsTests
{
	
	public function getClassDBUtilsName()
	{
		return "UserDBUtils";
	}
	
	/**
	 * method getClassName
	 * when called
	 * should returnCorrectly
	 */
	public function test_getClassName_called_returnCorrectly()
	{
		$expected = "User";
		$actual = $this->service->getClasDBName();
		$this->assertEquals($expected, $actual);
	}

}

?>