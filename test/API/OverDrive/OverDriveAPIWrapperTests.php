<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveAPIWrapper.php';

class OverDriveAPIWrapperTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	const clientKey = "DouglasCL";
	const clientSecret = "aha4lf0c2opJGfaRHxtkIEajvb3x2YKV";
	const libraryId = 1344;
	
	private static $accessToken;
	private static $productsUrl;
	
	public function setUp()
	{		
		$this->service = new OverDriveAPIWrapper();
		parent::setUp();		
	}
	
	
	/**
	* method login
	* when called
	* should returnCorrectLoginInfo
	*/
	public function test_01_login_called_returnCorrectLoginInfo()
	{
		$expected = "LIB META AVAIL SRCH";
		$result = $this->service->login(self::clientKey, self::clientSecret);
		$this->assertEquals("stdClass", get_class($result));
		$this->assertEquals($expected, $result->scope);
		self::$accessToken = $result->access_token;
	}
	
	/**
	* method getInfoDCLLibrary
	* when called
	* should returnCorrectInfo
	*/
	public function test_getInfoDCLLibrary_called_returnCorrectInfo()
	{
		$result = $this->service->getInfoDCLLibrary(self::$accessToken, self::libraryId);
		$this->assertEquals(self::libraryId, $result->id);
		self::$productsUrl = $result->links->products->href;
	}
	
	/**
	* method getDigitalCollection
	* when called
	* should returnCorrectInfo
	*/
	public function test_getDigitalCollection_called_returnCorrectInfo()
	{
		$result = $this->service->getDigitalCollection(self::$accessToken, self::$productsUrl, 3);
		$this->assertEquals(3, count($result->products));
	}
	
	/**
	* method getItemAvailability
	* when called
	* should executesCorrectly
	*/
	public function test_getItemAvailability_called_executesCorrectly()
	{
		$itemId="30AF0828-3A80-4701-938F-D867930A0D88";
		$result = $this->service->getItemAvailability(self::$accessToken, self::$productsUrl, $itemId);
		$this->assertInternalType("boolean",$result->available);
	}
	
	/**
	* method getItemMetadata
	* when called
	* should executesCorrectly
	*/
	public function test_getItemMetadata_called_executesCorrectly()
	{
		$itemId="EAF3F85A-C3BF-4B77-9E90-B64CE20672F8";
		//$itemId = "FCD82636-1A43-4B06-9977-B17250A6E9E1";
		$result = $this->service->getItemMetadata(self::$accessToken, self::$productsUrl, $itemId);
		$this->assertEquals($itemId,$result->id);
		$this->assertNotEmpty($result->mediaType);
	}

	
}

?>