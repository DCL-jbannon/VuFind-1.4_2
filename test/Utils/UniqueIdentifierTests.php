<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/UniqueIdentifier.php';

class UniqueIdentifierTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
		
	/**
	* method getUID 
	* when withNoPrefix
	* should returnA23CharacterLong
	*/
	public function test_getUID_withNoPrefix_returnA23CharacterLong()
	{
		$result = UniqueIdentifier::get();
		$this->assertEquals(2, count(explode("-", $result)));
		
	}
	
	/**
	 * method getUID
	 * when withPrefix
	 * should returnA23CharacterLong
	 */
	public function test_getUID_withPrefix_returnA23CharacterLong()
	{
		$prefix = "aDummyPrefix";
		$result = UniqueIdentifier::get($prefix);
		$this->assertEquals(3, count(explode("-", $result)));
	}
	
	/**
	 * method getUID
	 * when withPrefixWithDashes
	 * should returnA23CharacterLong
	 */
	public function test_getUID_withPrefixWithDashes_returnA23CharacterLong()
	{
		$expected = "aDummyPrefix";
		$prefix =   "aDu-m-my-Prefix-";
		$result = UniqueIdentifier::get($prefix);
		
		$partsResults =  explode("-", $result);
		$this->assertEquals(3, count($partsResults));
		$this->assertEquals($expected, $partsResults[0]);
	}
	
			
	
	
}

?>