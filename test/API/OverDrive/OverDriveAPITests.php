<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveAPI.php';
require_once dirname(__FILE__).'/../../mother/OverDriveAPI/resultsMother.php';

class OverDriveAPITests extends PHPUnit_Framework_TestCase
{
	const clientKey = "aDummyClientKey";
	const clientSecret = "aDummyClientSecret";
	const LibraryId = 1344;
	private $service;
	private $overDriveAPIWrapperMock;
	private $resultsODAPIMother;
	
	public function setUp()
	{
		$this->resultsODAPIMother = new OverDriveResultsMother();
		$this->overDriveAPIWrapperMock = $this->getMock("IOverDriveAPIWrapper", array("login", "getInfoDCLLibrary", "getItemMetadata",
				           															  "getDigitalCollection", "getItemAvailability"));
		$this->service = new OverDriveAPI(self::clientKey, self::clientSecret, self::LibraryId, $this->overDriveAPIWrapperMock);
		parent::setUp();		
	}
	
	
	/**
	* method login
	* when called
	* should executesCorrectly
	*/
	public function test_login_called_executesCorrectly()
	{
		$resultLogin = $this->resultsODAPIMother->getValidLoginResult();
		$this->overDriveAPIWrapperMock->expects($this->once())
									  ->method("login")
									  ->with($this->equalTo(self::clientKey), $this->equalTo(self::clientSecret))
									  ->will($this->returnValue($resultLogin));
		
		$actual = $this->service->login();
		$this->assertEquals($resultLogin, $actual);
		$this->assertEquals($resultLogin->access_token, $this->service->getAccessToken());
	}
	
	
	/**
	* method getInfoDCLLibrary
	* when called
	* should executesCorrectly
	*/
	public function test_getInfoDCLLibrary_called_executesCorrectly()
	{
		$this->service->setAccessToken("aDummtAccessToken");
		$resultInfoLibrary = $this->resultsODAPIMother->getInfoLibraryResult();
		
		$this->overDriveAPIWrapperMock->expects($this->once())
									  ->method("getInfoDCLLibrary")
									  ->with($this->equalTo("aDummtAccessToken"), $this->equalTo(self::LibraryId))
									  ->will($this->returnValue($resultInfoLibrary));
		
		$actual = $this->service->getInfoDCLLibrary();
		$this->assertEquals($resultInfoLibrary, $actual);
		$this->assertEquals($resultInfoLibrary->links->products->href, $this->service->getProductsUrl());
	}
		
  /**
  * method getDigitalCollection
  * when called
  * should executesCorrectl
  */
  public function test_getDigitalCollection_called_executesCorrectl()
  {
  		$this->service->setAccessToken("aDummyAccessToken");
  		$this->service->setProductsUrl("aDummyProductsUrl");
  		$limit = 25;
  		$offset = 123;
  		$expected = "aDummyResult";
  		
  		$this->overDriveAPIWrapperMock->expects($this->once())
								  		->method("getDigitalCollection")
								  		->with($this->equalTo("aDummyAccessToken"), $this->equalTo("aDummyProductsUrl"),
								  			   $this->equalTo($limit), $this->equalTo($offset))
								  		->will($this->returnValue($expected));
  		
  		$actual = $this->service->getDigitalCollection($limit, $offset);
  		$this->assertEquals($expected, $actual);
  }
  
  /**
  * method getItemAvailability
  * when called
  * should executesCorrectly
  */
  public function test_getItemAvailability_called_executesCorrectly()
  {
  		$expected = "aDummyResult";
	  	$this->service->setAccessToken("aDummyAccessToken");
	  	$this->service->setProductsUrl("aDummyProductsUrl");
	  	$itemId = "aDummyItemId";
	  	$this->overDriveAPIWrapperMock->expects($this->once())
	  									->method("getItemAvailability")
	  									->with($this->equalTo("aDummyAccessToken"), 
	  										   $this->equalTo("aDummyProductsUrl"),
	  										   $this->equalTo($itemId))
	  									->will($this->returnValue($expected));
	  	$actual = $this->service->getItemAvailability($itemId);
	  	$this->assertEquals($expected, $actual);
  }
  
  /**
   * method getItemMetadata
   * when called
   * should executesCorrectly
   */
  public function test_getItemMetadata_called_executesCorrectly()
  {
  	$expected = "aDummyResult";
  	$this->service->setAccessToken("aDummyAccessToken");
  	$this->service->setProductsUrl("aDummyProductsUrl");
  	$itemId = "aDummyItemId";
  	$this->overDriveAPIWrapperMock->expects($this->once())
								  	->method("getItemMetadata")
								  	->with($this->equalTo("aDummyAccessToken"), $this->equalTo("aDummyProductsUrl"),
								  		   $this->equalTo($itemId))
								  	->will($this->returnValue($expected));
  	$actual = $this->service->getItemMetadata($itemId);
  	$this->assertEquals($expected, $actual);
  }
  

}


?>