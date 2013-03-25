<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveSS.php';

class OverDriveSSTests extends PHPUnit_Framework_TestCase
{
	const baseUrl = "dcl.lib.overdrive.com";
	const baseSecureURL = "https://secure38.libraryreserve.com/";
	const theme = "/10/50/en/";
	const libraryCardILS = "douglascounty";
	const username = "23025003575917";
	
	private $service;	
	private static $session;
	
	const itemToCheckOut = "EEAC2AEE-0FA6-462F-A967-68C665BDD15C";
	const itemCheckedOutOtherUser = "82CDD641-857A-45CA-8775-34EEDE35B238";
	const itemToPlaceHold = "82CDD641-857A-45CA-8775-34EEDE35B238";
	const itemAddWishList = "06051A30-5536-4C56-B8CC-20508BF9BDF2";
	const formatIdItemCheckOut = 420;
	const formatIdPlaceHold = 420;
		
	public function setUp()
	{
		$this->service = new OverDriveSS(self::baseUrl, self::theme, self::libraryCardILS, self::baseSecureURL);
		parent::setUp();
	}
	
	/**
	* method getSession 
	* when called
	* should executesCorrectly
	*/
	public function test_001_getSession_called_executesCorrectly()
	{
		self::$session = $this->service->getSession();
	}
	
	/**
	* method login 
	* when badLogin
	* should returnFalse
	*/
	public function test_002_login_badLogin_returnFalse()
	{
		$actual = $this->service->login("aNonValidSession", self::username);
		$this->assertFalse($actual);
	}
	
	/**
	* method checkLogin 
	* when called
	* should returnTrue
	*/
	public function test_003_login_called_executesCorrectly()
	{
		$actual = $this->service->login(self::$session, self::username);
		$this->assertTrue($actual);
	}

	/**
	* method checkOut 
	* when called
	* should executesCorrectly
	*/
	public function test_004_checkOut_called_executesCorrectly()
	{
		$actual = $this->service->checkOut(self::$session, self::itemToCheckOut, self::formatIdItemCheckOut, false);
		$this->assertTrue($actual, $this->service->getErrorMessage());
	}
	
	/**
	* method checkOut
	* when cannotBeCheckedOutAgain
	* should returnFalse
	*/
	public function test_005_checkOut_cannotBeCheckedOutAgain_returnFalse()
	{
		$actual = $this->service->checkOut(self::$session, self::itemToCheckOut, self::formatIdItemCheckOut);
		$this->assertFalse($actual);
		$this->assertEquals(220, $this->service->getErrorCode());
	}
	
	/**
	* method placeHold 
	* when called
	* should returnTrue
	*/
	public function test_007_placeHold_called_returnTrue()
	{
		$actual = $this->service->placeHold(self::$session, self::itemToPlaceHold, self::formatIdPlaceHold, "aDummyEmail@mailinator.com");
		$this->assertTrue($actual);
	}
	
	/**
	 * method placeHold
	 * when alreadyOnHold
	 * should returnFalse
	 */
	public function test_008_placeHold_alreadyOnHold_returnFalse()
	{
		$actual = $this->service->placeHold(self::$session, self::itemToPlaceHold, self::formatIdPlaceHold, "aDummyEmail@mailinator.com");
		$this->assertFalse($actual);
	}

	/**
	* method addToWishList 
	* when called
	* should returnTrue
	*/
	public function test_011_addToWishList_called_returnTrue()
	{
		$actual = $this->service->addToWishList(self::$session, self::itemAddWishList);
		$this->assertTrue($actual);
	}
	
	/**
	 * method addToWishList
	 * when alreadyAdded
	 * should returnTrue
	 */
	public function test_012_addToWishList_alreadyAdded_returnTrue()
	{
		$actual = $this->service->addToWishList(self::$session, self::itemAddWishList);
		$this->assertTrue($actual);
	}
	
	/**
	* method changeLendingOptions 
	* when called
	* should executesCorrectly
	*/
	public function test_014_changeLendingOptions_called_executesCorrectly()
	{
		$actual = $this->service->changeLendingOptions(self::$session, 7, 21, 3, 21);
		$this->assertTrue($actual);
	}
	
	/**
	 * method getLendingOptions
	 * when called
	 * should executesCorrectly
	 */
	public function test_015_getLendingOptions_called_executesCorrectly()
	{
		$expected->ebook = 7;
		$expected->audio = 21;
		$expected->video = 3;
		$expected->disney = 21;
		$actual = $this->service->getLendingOptions(self::$session);
		$this->assertEquals($expected, $actual);
		
		$expected->ebook = 14;
		$expected->audio = 7;
		$expected->video = 7;
		$expected->disney = 7;
		$actual = $this->service->changeLendingOptions(self::$session, 14, 7, 7, 7);
		$actual = $this->service->getLendingOptions(self::$session);
		
		
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getItemDetails
	* when called
	* should executesCorrectly
	*/
	public function test_getItemAvailability_called_executesCorrectly()
	{
		$actual = $this->service->getItemDetails(self::$session, self::itemCheckedOutOtherUser);
		$this->assertTrue(isset($actual->AvailableCopies));
		$this->assertTrue(isset($actual->TotalCopies));
		$this->assertTrue(isset($actual->OnHoldCount));
		$this->assertTrue(isset($actual->CanCheckout));
		$this->assertTrue(isset($actual->CanHold));
		$this->assertTrue(isset($actual->CanAddWishList));
	}
	
	
	/**
	* method getMultipleItemsDetails 
	* when called
	* should executesCorrectly
	*/
	public function test_getMultipleItemsDetails_called_executesCorrectly()
	{
		$itemId1 = self::itemCheckedOutOtherUser;
		$itemId2 = self::itemAddWishList;
		
		$actual = $this->service->getMultipleItemsDetails(self::$session, array($itemId1, $itemId2));

		$this->assertEquals(2, count($actual));
		$this->assertTrue(isset($actual[$itemId1]));
		$this->assertTrue(isset($actual[$itemId2]));
	}
	
	/**
	* method getPatronCirculation 
	* when called
	* should executesCorrectly
	*/
	public function test_getPatronCirculation_called_executesCorrectly()
	{
		$actual = $this->service->getPatronCirculation(self::$session);
		
		$this->assertNotEmpty($actual->Checkouts);
		$this->assertNotEmpty($actual->Holds);
		$this->assertNotEmpty($actual->WishList);
		
		$this->assertEquals(self::itemToCheckOut,  $actual->Checkouts[0]['ItemId']);
		$this->assertEquals(self::itemToPlaceHold, $actual->Holds[0]['ItemId']);
		$this->assertEquals(self::itemAddWishList, $actual->WishList[0]['ItemId']);
	}
	
	//UTM TEAR DOWN
	/**
	 * method returnTitle
	 * when hasNotBeenDownloaded
	 * should executesCorrectly
	 */
	public function test_050_returnTitle_hasNotBeenDownloaded_executesCorrectly()
	{
		$actual = $this->service->returnTitle(self::$session, self::itemToCheckOut);
		$this->assertTrue($actual, $this->service->getErrorMessage());
	}
	
	/**
	 * method cancelHold
	 * when called
	 * should returnsTrue
	 */
	public function test_051_cancelHold_called_returnsTrue()
	{
		$actual = $this->service->cancelHold(self::$session, self::itemToPlaceHold, self::formatIdPlaceHold);
		$this->assertTrue($actual);
	}
	
	/**
	 * method cancelHold
	 * when userHasNotPacedHold
	 * should returnsTrue
	 */
	public function test_052_cancelHold_userHasNotPacedHold_returnsTrue()
	{
		$actual = $this->service->cancelHold(self::$session, self::itemToPlaceHold, self::formatIdPlaceHold);
		$this->assertFalse($actual);
	}

	/**
	 * method removeWishList
	 * when itemIsOnWishList
	 * should returnTrue
	 */
	public function test_053_removeWishList_itemIsOnWishList_returnTrue()
	{
		$actual = $this->service->removeWishList(self::$session, self::itemAddWishList);
		$this->assertTrue($actual);
	}
	
}
?>