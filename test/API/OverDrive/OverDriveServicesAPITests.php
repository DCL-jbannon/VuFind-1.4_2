<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveServicesAPI.php';
require_once dirname(__FILE__).'/../../mother/OverDriveAPI/resultsMother.php';

class OverDriveServicesAPITests extends PHPUnit_Framework_TestCase
{
	const clientKey = "aDummyClientKey";
	const clientSecret = "aDummyClientSecret";
	const libraryId = 1344;
	
	//Screen Scraping
	const baseUrl = "aDummyBaseUrl";
	const theme = "aDummyTheme";
	const libraryCardILS = "aDummyLibraryCardILS";
	const baseSecureUrl = "aDummyBaseSecureUrl";
	
	private $service;
	private $overDriveAPIMock;
	private $odMother;
	
	public function setUp()
	{	
		$this->odMother = new OverDriveResultsMother();
		
		$this->overDriveAPIMock = $this->getMock("IOverDriveAPI",array("getItemAvailability", "login", "getAccessToken", "getItemMetadata", "getInfoDCLLibrary", 
																	   "getSession", "loginSS", "checkOut", "placeHold", "cancelHold", "addToWishList", "removeWishList",
																	   "changeLendingOptions", "getLendingOptions", "getItemDetails",
															           "getPatronCirculation", "chooseFormat"));
		
		$this->service = new OverDriveServicesAPI($this->overDriveAPIMock);
		parent::setUp();		
	}
	
	/**
	 * method getItemInfo
	 * when notLogged
	 * should executesCorrectly
	 * @dataProvider DP_getItemInfo
	 */
	public function test_getItemInfo_notLogged_executesCorrectly($methodToTest)
	{
		$itemId = "aDummyItemId";
		$expected= "aDummyValue";
		
		$this->prepareLogin();
		$this->prepareGetInfoDCLLibrary();
		
		$this->overDriveAPIMock->expects($this->once())
								->method($methodToTest)
								->with($this->equalTo($itemId))
								->will($this->returnValue($expected));
	
		$actual = $this->service->$methodToTest($itemId);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getItemInfo()
	{
		return array(
						array("getItemAvailability"),
						array("getItemMetadata")
					);
	}
	
	/**
	 * method getItemInfo
	 * when tokenisNotValid
	 * should executesCorrectly
	 * @dataProvider DP_getItemInfo
	 */
	public function test_getItemInfo_tokenisNotValid_executesCorrectly($methodToTest)
	{
		$itemId = "aDummyItemId";
		$expected= "aDummyValue";
		
		OverDriveAPI::$accessToken = "aDummyNonValidToken";
		$this->prepareLogin();
		$this->prepareGetInfoDCLLibrary();
	
		$this->prepareOverDriveAPIMethod(0, $methodToTest, $itemId, $this->throwException(new OverDriveTokenExpiredException()));
		/**
		 * http://bit.ly/VjFlHJ
		 * Note that the counter is per-mock across all method calls received to it. Thus if there are going to be two intervening calls to $pdo, you would use 0 and 3
		 */
		$this->prepareOverDriveAPIMethod(3, $methodToTest, $itemId, $this->returnValue($expected));
	
		$actual = $this->service->$methodToTest($itemId);
		$this->assertEquals($expected, $actual);
	
	}
	
	/**
	* method checkout 
	* when called
	* should executeCorrectly
	*/
	public function test_checkout_called_executeCorrectly()
	{
		$expected = "aDummyResult";
		$username = "aDummyUsername";
		$itemId = "aDummyItemId";
		$formatId = "aDummyFormatId";
		
		$this->prepareGetSessionAndLoginSS($username);
		$this->overDriveAPIMock->expects($this->once())
								->method("checkOut")
								->with($this->equalTo($itemId), $this->equalTo($formatId))
								->will($this->returnValue($expected));
		
		$actual = $this->service->checkOut($username, $itemId, $formatId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method placeHold
	 * when called
	 * should executesCorrectly
	 */
	public function test_placeHold_called_executesCorrectly()
	{
		$username = "aDummyUsername";
		$email = "aDummyEmail";
		$itemId = "aDummyItemId";
		$formatId = "25";
	
		$itemDetailsHoldOption = $this->odMother->getItemDetailsHoldOption();
		
		$this->prepareGetSessionAndLoginSS($username);
		
		$this->overDriveAPIMock->expects($this->once())
								->method("getItemDetails")
								->with($this->equalTo($itemId))
								->will($this->returnValue($itemDetailsHoldOption));
		
		
		$this->overDriveAPIMock->expects($this->once())
								->method("placeHold")
								->with($this->equalTo($itemId), $this->equalTo($formatId), $this->equalTo($email));
	
		$actual = $this->service->placeHold($username, $itemId, $email);
		$this->assertTrue($actual);
	}
	
	/**
	 * method cancelHold
	 * when called
	 * should executesCorrectly
	 */
	public function test_cancelHold_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$username = "aDummyUsername";
		$itemId = "82CDD641-857A-45CA-8775-34EEDE35B238";
		$formatId = 50;
	
		$resultGetPatronCirculation = $this->odMother->getPatronCirculation();
		$this->prepareGetSessionAndLoginSS($username);
		
		$this->overDriveAPIMock->expects($this->once())
							   ->method("getPatronCirculation")
							   ->will($this->returnValue($resultGetPatronCirculation));
		
		$this->overDriveAPIMock->expects($this->once())
								->method("cancelHold")
								->with($this->equalTo($itemId), $this->equalTo($formatId))
								->will($this->returnValue($expected));
	
		$actual = $this->service->cancelHold($username, $itemId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method addToWishList 
	* when called
	* should executesCorrectly
	*/
	public function test_addToWishList_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$username = "aDummyUsername";
		$itemId = "aDummyItemId";

		$this->prepareGetSessionAndLoginSS($username);
		$this->overDriveAPIMock->expects($this->once())
								->method("addToWishList")
								->with($this->equalTo($itemId))
								->will($this->returnValue($expected));
								
		$actual = $this->service->addToWishList($username, $itemId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method removeWishList
	 * when called
	 * should executesCorrectly
	 */
	public function test_removeWishList_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$username = "aDummyUsername";
		$itemId = "aDummyItemId";
	
		$this->prepareGetSessionAndLoginSS($username);
		$this->overDriveAPIMock->expects($this->once())
								->method("removeWishList")
								->with($this->equalTo($itemId))
								->will($this->returnValue($expected));
	
		$actual = $this->service->removeWishList($username, $itemId);
		$this->assertEquals($expected, $actual);
	}	
		
	/**
	* method changeLendingOptions 
	* when called
	* should executesCorrectly
	*/
	public function test_changeLendingOptions_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$username = "aDummyUsername";
		$ebookDays = "aDummyEbookLendingDays"; $audioDays = "";
		$videoDays = "aDummyVideoLendingDays"; $disneyDays = "aDummyDisneyLendingDays";
		
		$this->prepareGetSessionAndLoginSS($username);
		$this->overDriveAPIMock->expects($this->once())
								->method("changeLendingOptions")
								->with($this->equalTo($ebookDays),
									   $this->equalTo($audioDays),
									   $this->equalTo($videoDays),
									   $this->equalTo($disneyDays))
								->will($this->returnValue($expected));
								
		$actual = $this->service->changeLendingOptions($username, $ebookDays, $audioDays, $videoDays, $disneyDays);
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
		$username = "aDummyUsername";
		$this->prepareGetSessionAndLoginSS($username);
		$this->overDriveAPIMock->expects($this->once())
								->method("getLendingOptions")
								->will($this->returnValue($expected));
		
		$actual = $this->service->getLendingOptions($username);
		$this->assertEquals($expected, $actual);
	}
	
		
	/**
	* method getItemDetails 
	* when noUsernameProvided
	* should executesCorrectly
	*/
	public function test_getItemDetails_noUsernameProvided_executesCorrectly()
	{
		$expected = "aDummyResult";
		$itemId = "aDummyItemId";
		
		$this->overDriveAPIMock->expects($this->once())
								->method("getSession");
		
		$this->overDriveAPIMock->expects($this->once())
								->method("getItemDetails")
								->will($this->returnValue($expected));
			
		$actual = $this->service->getItemDetails($itemId, NULL);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getItemDetails
	 * when usernameProvided
	 * should executesCorrectly
	 */
	public function test_getItemDetails_usernameProvided_executesCorrectly()
	{
		$expected = "aDummyResult";
		$itemId = "aDummyItemId";
		$username = "aDummyUsername";
		
		$this->prepareGetSessionAndLoginSS($username);
		$this->overDriveAPIMock->expects($this->once())
								->method("getItemDetails")
								->will($this->returnValue($expected));
			
		$actual = $this->service->getItemDetails($itemId, $username);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMultipleItemsDetail
	 * when noUsernameProvided
	 * should executesCorrectly
	 */
	public function test_getMultipleItemsDetail_noUsernameProvided_executesCorrectly()
	{
		$expected = array("aDummyResult");
		$itemsId = array("aDummyItemId", "anotherDummtItemId");
	
		$this->overDriveAPIMock->expects($this->once())
								->method("getSession");
	
		$this->overDriveAPIMock->expects($this->once())
								->method("getItemDetails")
								->will($this->returnValue($expected));
			
		$actual = $this->service->getMultipleItemsDetail($itemsId, NULL);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getMultipleItemsDetail
	 * when usernameProvided
	 * should executesCorrectly
	 */
	public function test_getMultipleItemsDetail_usernameProvided_executesCorrectly()
	{
		$expected = array("aDummyResult");
		$itemsId = array("aDummyItemId", "anotherDummtItemId");
		$username = "aDummyUsername";
	
		$this->prepareGetSessionAndLoginSS($username);
		$this->overDriveAPIMock->expects($this->once())
								->method("getItemDetails")
								->will($this->returnValue($expected));
			
		$actual = $this->service->getMultipleItemsDetail($itemsId, $username);
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
		$username = "aDummyUsername";
		$this->prepareGetSessionAndLoginSS($username);
		
		$this->overDriveAPIMock->expects($this->once())
								->method("getPatronCirculation")
								->will($this->returnValue($expected));
			
		$actual = $this->service->getPatronCirculation($username);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method allMethodUsesODAPI
	* when calledTwice
	* should notGetTwoAccessTokenAPI
	*/
	public function test_allMethodUsesODAPI_calledTwice_notGetTwoAccessTokenAPI()
	{
		$this->prepareLogin();
		$this->prepareGetInfoDCLLibrary();

		$this->service->getItemAvailability("aDummyOne");
		$this->service->getItemAvailability("aDummyOne");
	}
	
	/**
	 * method allMethodUsesODSS
	 * when calledTwice
	 * should notGetTwoSessionSS
	 */
	public function test_allMethodUsesODSS_calledTwice_notGetTwoSessionSS()
	{
		$username = "aDummyOne";
		$this->prepareGetSessionAndLoginSS($username);
			
		$actual = $this->service->getLendingOptions($username);
		$actual = $this->service->changeLendingOptions($username, 7, 7, 7, 7);
	}
	
	
	/**
	 * method chooseFormat
	 * when called
	 * should executesCorrectly
	 */
	public function test_chooseFormat_called_executesCorrectly()
	{
		$expected = "aDummyResultPatronCirculation";
		$username = "aDummyUsername";
		$itemId = "aDummtItemId";
		$formatId = "aDummyFormatId";
		$this->prepareGetSessionAndLoginSS($username);
	
		$this->overDriveAPIMock->expects($this->once())
								->method("chooseFormat")
								->with($this->equalTo($itemId), $this->equalTo($formatId))
								->will($this->returnValue($expected));
			
		$actual = $this->service->chooseFormat($username, $itemId, $formatId);
		$this->assertEquals($expected, $actual);
	}
	
	
	
	public function tearDown()
	{
		OverDriveAPI::$accessToken = NULL;
		OverDriveAPI::$session = NULL;
	}
	
	
	//PRIVATE
	
	private function prepareGetSessionAndLoginSS($username)
	{
		$this->overDriveAPIMock->expects($this->once())
								->method("getSession")
								->with($this->equalTo($username))
								->will($this->returnCallback(
																function()
																{
																	OverDriveAPI::$session = "aValidSessionSS";		
																}
															));
		
		$this->overDriveAPIMock->expects($this->once())
								->method("loginSS")
								->with($this->equalTo($username));
	}
	
	
	private function prepareOverDriveAPIMethod($at, $methodToTest, $itemId, $will)
	{
		$this->overDriveAPIMock->expects($this->at($at))
								->method($methodToTest)
								->with($this->equalTo($itemId))
								->will($will);
	}
	
	private function prepareGetAccessToken($result)
	{
		$this->overDriveAPIMock->expects($this->once())
								->method("getAccessToken")
								->will($this->returnValue($result));
	}
	
	private function prepareLogin()
	{
		$this->overDriveAPIMock->expects($this->once())
								->method("login")
								->will($this->returnCallback(
															  	function()
															  	{
															  		OverDriveAPI::$accessToken = "aValidDummyToken";
															    }
															));
	}
	
	private function prepareGetInfoDCLLibrary()
	{
		$this->overDriveAPIMock->expects($this->once())
								->method("getInfoDCLLibrary");
	}
	
}
?>