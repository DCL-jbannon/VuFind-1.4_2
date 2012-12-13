<?php

require_once dirname(__FILE__)."/../../vufind/classes/Utils/ObjectUtils.php";

class ObjectUtilsTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	/**
	* method is_a
	* when NotObject
	* should returnFalse
	*/
	public function test_is_a_NotObject_returnFalse()
	{
		$actual = ObjectUtils::is_a("string","String");
		$this->assertFalse($actual);	
	}
	
	/**
	 * method is_a
	 * when objectButDiferent
	 * should returnFalse
	 */
	public function test_is_a_objectButDiferent_returnFalse()
	{
		$actual = ObjectUtils::is_a(new stdClass(),"String");
		$this->assertFalse($actual);
	}
	
	/**
	 * method is_a
	 * when object
	 * should returntrue
	 */
	public function test_is_a_object_returntrue()
	{
		$actual = ObjectUtils::is_a(new stdClass(),"stdClass");
		$this->assertTrue($actual);
	}
	
}
?>