<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Freegal/FreegalAPIWrapper.php';

class FreegalAPIWrapperTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	const baseUrl = "https://freegalmusic.com";
	const APIKey = "c1b16052497962551ea7482fc86acc1ec3b39ace";
	const libraryId = "11";
	const patronID = "23025006182976";
	
	
	public function setUp()
	{
		$this->service = new FreegalAPIWrapper(self::baseUrl, self::APIKey, self::libraryId, self::patronID);
		parent::setUp();		
	}
	
	
	/**
	* method getSongsByTypeSearch
	* when called
	* should executesCorrectly
	*/
	public function test_getSongsByTypeSearch_called_executesCorrectly()
	{
		$expected = "221776";
		$actual = $this->service->getSongsByTypeSearch("album","Sempre Sardanes");
		$this->assertEquals($expected, (string)$actual->Song->ProductID);
	}
	
	/**
	* method getSongByTypeSearch
	* when albumNotFound
	* should returnNoRecords
	*/
	public function test_getSongByTypeSearch_albumNotFound_returnEmpty()
	{
		$expected = "No Records";
		$actual = $this->service->getSongsByTypeSearch("album","aDummyAlbummThatDoesNotExists");
		$this->assertEquals($expected, (string)$actual->message);
	}
	
	/**
	* method getSongByTypeSearch 
	* when albumNameThrow404FreegalError
	* should returnNoRecordsMessage
	*/
	public function test_getSongByTypeSearch_albumNameThrow404FreegalError_returnNoRecordsMessage()
	{
		$expected = "No Records";
		$actual = $this->service->getSongsByTypeSearch("album","The Music of Cuba - Cuban Soneros, Vol. 2 / 1938 - 1956");
		$this->assertEquals($expected, (string)$actual->message);
	}
	
	
	/**
	* method search 
	* when called
	* should executesCorrectly
	*/
	public function test_search_called_executesCorrectly()
	{
		$parameters['artist'] = "Arden";
		$parameters['album'] = "Pursuit";
		$actual = $this->service->search($parameters);
		$this->assertNotEmpty($actual->Song);
	}
}
?>