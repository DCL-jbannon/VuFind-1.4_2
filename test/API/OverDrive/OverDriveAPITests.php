<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveAPI.php';
require_once dirname(__FILE__).'/../../mother/OverDriveAPI/resultsMother.php';

class OverDriveAPITests extends PHPUnit_Framework_TestCase
{
	//OverDrive Wrapper API
	const clientKey = "aDummyClientKey";
	const clientSecret = "aDummyClientSecret";
	const LibraryId = 1344;
	
	const accessToken = "aDummyAccessToken";
	const productsUrl = "aDummyProductsUrl";
	
	//OverDrive Screen Scraping
	const baseUrl = "aDummyBaseUrl";
	const theme = "aDummyTheme";
	const libraryCardILS = "aDummyLibraryCardILS";
	const baseSecureUrl = "aDummyBaseSecureUrl";
	
	const session = "aDummySession";
	
	
	//TEST Variables
	private $service;
	private $overDriveAPIWrapperMock;
	private $resultsODAPIMother;
	private $overDriveSSMock;

	
	public function setUp()
	{
		global $configArray;
		
		$this->resultsODAPIMother = new OverDriveResultsMother();
		$this->overDriveAPIWrapperMock = $this->getMock("IOverDriveAPIWrapper", array("login", "getInfoDCLLibrary", "getItemMetadata",
				           															  "getDigitalCollection", "getItemAvailability"));
		
		$this->overDriveSSMock = $this->getMock("IOverDriveSS", array("getSession", "login", "checkOut", "placeHold", "cancelHold", 
																	  "addToWishList", "removeWishList", "changeLendingOptions", 
																	  "getLendingOptions", "getItemDetails","getMultipleItemsDetails",
																	  "getPatronCirculation", "returnTitle", "chooseFormat"));
		
		
		$configArray['OverDrive']['url'] = self::baseUrl;
		$configArray['OverDrive']['theme'] = self::baseUrl;
		$configArray['OverDrive']['LibraryCardILS'] = self::baseUrl;
		$configArray['OverDrive']['baseSecureUrl'] = self::baseUrl;
		
		$configArray['OverDriveAPI']['clientKey'] = self::clientKey;
		$configArray['OverDriveAPI']['clientSecret'] = self::clientSecret;
		$configArray['OverDriveAPI']['libraryId'] = self::LibraryId;
		
		$this->service = new OverDriveAPI($this->overDriveAPIWrapperMock, 
										  $this->overDriveSSMock);
		parent::setUp();		
	}
	
	
	/**
	* method login
	* when called
	* should executesCorrectly
	*/
	public function test_login_called_executesCorrectly()
	{
		$resultLogin = $this->resultsODAPIMother->getValidLoginResult(self::accessToken);
		
		$this->overDriveAPIWrapperMock->expects($this->once())
									  ->method("login")
									  ->with($this->equalTo(self::clientKey), $this->equalTo(self::clientSecret))
									  ->will($this->returnValue($resultLogin));
		
		$actual = $this->service->login();
		$this->assertEquals($resultLogin, $actual);
	}
	
	
	/**
	* method getInfoDCLLibrary
	* when called
	* should executesCorrectly
	*/
	public function test_getInfoDCLLibrary_called_executesCorrectly()
	{
		
		$resultInfoLibrary = $this->resultsODAPIMother->getInfoLibraryResult(self::productsUrl);
		
		$this->overDriveAPIWrapperMock->expects($this->once())
									  ->method("getInfoDCLLibrary")
									  ->with($this->equalTo(self::accessToken), $this->equalTo(self::LibraryId))
									  ->will($this->returnValue($resultInfoLibrary));
		
		$actual = $this->service->getInfoDCLLibrary();
		$this->assertEquals($resultInfoLibrary, $actual);
	}
		
  /**
  * method getDigitalCollection
  * when called
  * should executesCorrectl
  */
  public function test_getDigitalCollection_called_executesCorrectl()
  {
  		$limit = 25;
  		$offset = 123;
  		$expected = "aDummyResult";
  		
  		$this->overDriveAPIWrapperMock->expects($this->once())
								  		->method("getDigitalCollection")
								  		->with($this->equalTo(self::accessToken), $this->equalTo(self::productsUrl),
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
	  	$itemId = "aDummyItemId";
	  	$this->overDriveAPIWrapperMock->expects($this->once())
	  									->method("getItemAvailability")
	  									->with($this->equalTo(self::accessToken), 
	  										   $this->equalTo(self::productsUrl),
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
  	$itemId = "aDummyItemId";
  	$this->overDriveAPIWrapperMock->expects($this->once())
								  	->method("getItemMetadata")
								  	->with($this->equalTo(self::accessToken), $this->equalTo(self::productsUrl),
								  		   $this->equalTo($itemId))
								  	->will($this->returnValue($expected));
  	$actual = $this->service->getItemMetadata($itemId);
  	$this->assertEquals($expected, $actual);
  }
  
  //OverDrive Screen Scraping
  /**
  * method getSession 
  * when called
  * should executesCorrectly
  */
  public function test_getSession_called_executesCorrectly()
  {
  	  $expected = self::session;
  	  $this->overDriveSSMock->expects($this->once())
  	  						->method("getSession")
  	  						->will($this->returnValue(self::session));
  	  
  	  $actual = $this->service->getSession();
  	  $this->assertEquals($expected, $actual);  	  
  }
  
  /**
  * method loginSS 
  * when called
  * should returnsBoolean
  */
  public function test_loginSS_called_returnsBoolean()
  {
  		$expected = "aDummyBooleanResult";
  		$username = "aDummyUsername";
  		$this->overDriveSSMock->expects($this->once())
						  		->method("login")
						  		->with($this->equalTo(self::session), $this->equalTo($username))
						  		->will($this->returnValue($expected));
  	
  		$actual = $this->service->loginSS($username);
  		$this->assertEquals($expected, $actual);
  }
  
  /**
  * method checkOut 
  * when called
  * should returnsBoolean
  */
  public function test_checkOut_called_returnsBoolean()
  {
  		$expected = "aDummyBooleanResult";
  		$itemId = "aDummyItemId";
  		$formatId = "aDummyFormatId";
  		
  		$this->overDriveSSMock->expects($this->once())
						  		->method("checkOut")
						  		->with($this->equalTo(self::session), $this->equalTo($itemId),  $this->equalTo($formatId))
						  		->will($this->returnValue($expected));
  		
  		$actual = $this->service->checkOut($itemId, $formatId);
  		$this->assertEquals($expected, $actual);
  }
  
  /**
  * method placeHold 
  * when called
  * should returnsBoolean
  */
  public function test_placeHold_called_returnsBoolean()
  {
	  	$expected = "aDummyBooleanResult";
	  	$itemId = "aDummyItemId";
	  	$formatId = "aDummyFormatId";
	  	$email = "aDummyemail";
	  	
	  	$this->overDriveSSMock->expects($this->once())
							  	->method("placeHold")
							  	->with($this->equalTo(self::session), $this->equalTo($itemId),  
							  										  $this->equalTo($formatId), 
							  										  $this->equalTo($email))
							  	->will($this->returnValue($expected));
	  	
	  	$actual = $this->service->placeHold($itemId, $formatId, $email);
	  	$this->assertEquals($expected, $actual);
  }
  
  	
  /**
  * method cancelHold 
  * when called
  * should returnsBoolean
  */
  public function test_cancelHold_called_returnsBoolean()
  {
	  	$expected = "aDummyBooleanResult";
	  	$itemId = "aDummyItemId";
	  	$formatId = "aDummyFormatId";
	  	
	  	$this->overDriveSSMock->expects($this->once())
							  	->method("cancelHold")
							  	->with($this->equalTo(self::session), $this->equalTo($itemId),
							  										  $this->equalTo($formatId))
							  	->will($this->returnValue($expected));
	  	
	  	$actual = $this->service->cancelHold($itemId, $formatId);
	  	$this->assertEquals($expected, $actual);
  }
  
  /**
  * method addToWishList 
  * when called
  * should returnsBoolean
  * @dataProvider DP_wishList
  */
  public function test_addToWishList_called_returnsBoolean($methodToTest)
  {
	  	$expected = "aDummyBooleanResult";
	  	$itemId = "aDummyItemId";
	  	
	  	$this->overDriveSSMock->expects($this->once())
	  							->method($methodToTest)
	  							->with($this->equalTo(self::session), $this->equalTo($itemId))
	  							->will($this->returnValue($expected));
	  	
	  	$actual = $this->service->$methodToTest($itemId);
	  	$this->assertEquals($expected, $actual);
  }
  
  public function DP_wishList()
  {
  		return array(array("addToWishList"), array("removeWishList"));
  }
  
  /**
  * method changeLendingOptions 
  * when called
  * should returnsBoolean
  */
  public function test_changeLendingOptions_called_returnsBoolean()
  {
  		$expected = "aDummyBooleanResult";
  		$ebookDays = "aDummmyDaysForEbooks";
  		$audioBookDays = "aDummmyDaysForAudioEbooks";
  		$videoDays = "aDummmyDaysForVideo";
  		$disneyDays = "aDummmyDaysForDisney";
  		
  		$this->overDriveSSMock->expects($this->once())
						  		->method("changeLendingOptions")
						  		->with($this->equalTo(self::session), $this->equalTo($ebookDays), 
						  											  $this->equalTo($audioBookDays), 
						  											  $this->equalTo($videoDays),
						  											  $this->equalTo($disneyDays))
						  		->will($this->returnValue($expected));
  		
  		$actual = $this->service->changeLendingOptions($ebookDays, $audioBookDays, $videoDays, $disneyDays);
  		$this->assertEquals($expected, $actual);
  }
  
  /**
  * method getLendingOptions 
  * when called
  * should executesCorrectly
  */
  public function test_getLendingOptions_called_executesCorrectly()
  {
  		$expected = "aDummyResult";
  		$this->overDriveSSMock->expects($this->once())
						  		->method("getLendingOptions")
						  		->with($this->equalTo(self::session))
						  		->will($this->returnValue($expected));
  		
  		$actual = $this->service->getLendingOptions();
  		$this->assertEquals($expected, $actual);
  }
  
  /**
  * method getItemDetails 
  * when called
  * should executesCorrectly
  */
  public function test_getItemDetails_called_executesCorrectly()
  {
	  	$expected = "aDummyResult";
	  	$itemId = "aDummyItemId";
	  	$this->overDriveSSMock->expects($this->once())
							  	->method("getItemDetails")
							  	->with($this->equalTo(self::session), $this->equalTo($itemId))
							  	->will($this->returnValue($expected));
	  	
	  	$actual = $this->service->getItemDetails($itemId);
	  	$this->assertEquals($expected, $actual);
  }
  
  /**
   * method getItemDetails
   * when itemIdAsArrayOfItemId
   * should executesCorrectly
   */
  public function test_getItemDetails_itemIdAsArrayOfItemId_executesCorrectly()
  {
  	$expected = "aDummyResult";
  	$itemIds = array("aDummyItemId", "anotherDummyItemId");
  	$this->overDriveSSMock->expects($this->once())
						  	->method("getMultipleItemsDetails")
						  	->with($this->equalTo(self::session), $this->equalTo($itemIds))
						  	->will($this->returnValue($expected));
  
  	$actual = $this->service->getItemDetails($itemIds);
  	$this->assertEquals($expected, $actual);
  }
  
  /**
  * method getPatronCirculation 
  * when called
  * should executesCorrectly
  */
  public function test_getPatronCirculation_called_executesCorrectly()
  {
	  	$expected = "aDummyResultPatronCirculation";
	  	$this->overDriveSSMock->expects($this->once())
							  	->method("getPatronCirculation")
							  	->with($this->equalTo(self::session))
							  	->will($this->returnValue($expected));
	  	
	  	$actual = $this->service->getPatronCirculation();
	  	$this->assertEquals($expected, $actual);
  }
  
  /**
  * method chooseFormat 
  * when called
  * should executesCorrectly
  */
  public function test_chooseFormat_called_executesCorrectly()
  {
  	$expected = "aDummyResultChooseFormat";
  	$itemId = "aDummyItemId";
  	$formatId = "aDummyFormatId";
  	
  	$this->overDriveSSMock->expects($this->once())
						  	->method("chooseFormat")
						  	->with($this->equalTo(self::session), $this->equalTo($itemId), $this->equalTo($formatId))
						  	->will($this->returnValue($expected));
  	
  	$actual = $this->service->chooseFormat($itemId, $formatId);
  	$this->assertEquals($expected, $actual);
  }
  
  	

}
?>