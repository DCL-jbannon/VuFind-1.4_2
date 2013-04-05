<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveCacheSS.php';

class OverDriveCacheSSTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $overDriveSSMock;
	private $memcacheServicesMock;
	
	public function setUp()
	{
		$this->overDriveSSMock = $this->getMock("IOverDriveSS");
		$this->memcacheServicesMock = $this->getMock("IMemcacheServices", array("call", "set", "delete"));
		
		$this->service = new OverDriveCacheSS("", "", "", "", $this->overDriveSSMock, $this->memcacheServicesMock);
		parent::setUp();		
	}
	
	/**
	* method getItemDetails 
	* when called
	* should executesCorrectly
	*/
	public function test_getItemDetails_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
		$itemId = "aDummyItemId";
		
		$this->memcacheServicesMock->expects($this->once())
								   ->method("call")
								   ->with($this->equalTo($this->overDriveSSMock), 
								   		  $this->equalTo("getItemDetails"), 
								   		  $this->equalTo(array($session, $itemId)), 
								   		  $this->equalTo(OverDriveCacheSS::keyGetItemDetails.$session.$itemId),
								   		  $this->equalTo(30))
								   ->will($this->returnValue($expected));
		
		$actual = $this->service->getItemDetails($session, $itemId);
		$this->assertEquals($expected, $actual);	
	}
	
	/**
	 * method getPatronCirculation
	 * when called
	 * should executesCorrectly
	 */
	public function test_getPatronCirculation_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
	
		$this->memcacheServicesMock->expects($this->once())
									->method("call")
									->with($this->equalTo($this->overDriveSSMock),
											$this->equalTo("getPatronCirculation"),
											$this->equalTo(array($session)),
											$this->equalTo(OverDriveCacheSS::keyGetPatronCirculation.$session),
											$this->equalTo(300))
									->will($this->returnValue($expected));
	
		$actual = $this->service->getPatronCirculation($session);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getMultipleItemsDetails 
	* when called
	* should executesCorrectly
	*/
	public function test_getMultipleItemsDetails_called_executesCorrectly()
	{
		$session = "aDummySession";
		$itemId1 = "aDummyItemId1";
		$itemId2 = "aDummyItemId2";
		$resultItemId1 = "aDummtResultItemId1";
		$resultItemId2 = "aDummtResultItemId2";
		
		$itemsId = array($itemId1, $itemId2);
		
		$expected[$itemId1] = $resultItemId1;
		$expected[$itemId2] = $resultItemId2;
		
		$this->overDriveSSMock->expects($this->once())
							  ->method("getMultipleItemsDetails")
							  ->will($this->returnValue($expected));
		
		$this->memcacheServicesMock->expects($this->at(0))
									->method("set")
									->with($this->equalTo(OverDriveCacheSS::keyGetItemDetails.$session.$itemId1),
										   $this->equalTo($resultItemId1),
										   $this->equalTo(30));
		
		$this->memcacheServicesMock->expects($this->at(1))
									->method("set")
									->with($this->equalTo(OverDriveCacheSS::keyGetItemDetails.$session.$itemId2),
											$this->equalTo($resultItemId2),
											$this->equalTo(30));

		$actual = $this->service->getMultipleItemsDetails($session, $itemsId);
		$this->assertEquals($expected, $actual);
	}
	
		
	
	/**
	* method getSession 
	* when usernameNULL
	* should executesCorrectly
	*/
	public function test_getSession_usernameNULL_executesCorrectly()
	{
		$expected = "aDummyResult";
				
		$this->memcacheServicesMock->expects($this->once())
									->method("call")
									->with($this->equalTo($this->overDriveSSMock),
											$this->equalTo("getSession"),
											$this->equalTo(array(NULL)),
											$this->equalTo(OverDriveCacheSS::keySessionNoUsername),
											$this->equalTo(300))
									->will($this->returnValue($expected));
		
		$actual = $this->service->getSession(NULL);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getSession
	 * when usernameNotNULL
	 * should executesCorrectly
	 */
	public function test_getSession_usernameNotNULL_executesCorrectly()
	{
		$username = "aDummyUsername";
		$expected = "aDummyResult";
	
		$this->memcacheServicesMock->expects($this->once())
									->method("call")
									->with($this->equalTo($this->overDriveSSMock),
										   $this->equalTo("getSession"),
										   $this->equalTo(array($username)),
										   $this->equalTo(OverDriveCacheSS::keySessionUsername.$username),
										   $this->equalTo(300))
									->will($this->returnValue($expected));
	
		$actual = $this->service->getSession($username);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method login
	 * when called
	 * should executesCorrectly
	 */
	public function test_login_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
		$username = "aDummyUsername";
		
		$this->memcacheServicesMock->expects($this->once())
									->method("call")
									->with($this->equalTo($this->overDriveSSMock),
										   $this->equalTo("login"),
										   $this->equalTo(array($session, $username)),
										   $this->equalTo(OverDriveCacheSS::keyLogin.$session.$username),
										   $this->equalTo(300))
									->will($this->returnValue($expected));
	
		$actual = $this->service->login($session, $username);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method checkOut 
	* when called
	* should executesCorrectly
	*/
	public function test_checkOut_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
		$itemId = "aDummySession";
		$formatId = "aDummyFormatId";
		$download = true;
		
		$this->overDriveSSMock->expects($this->once())
								->method("checkOut")
								->with($this->equalTo($session), $this->equalTo($itemId), $this->equalTo($formatId), $this->equalTo($download))
								->will($this->returnValue($expected));
		
		$this->prepareDeleteGetPatronCirculationCacheKey($session);
		
		$actual = $this->service->checkOut($session, $itemId, $formatId, $download = true);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method returnTitle
	 * when called
	 * should executesCorrectly
	 */
	public function test_returnTitle_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
		$itemId = "aDummyItemId";
	
		$this->overDriveSSMock->expects($this->once())
								->method("returnTitle")
								->with($this->equalTo($session), $this->equalTo($itemId))
								->will($this->returnValue($expected));
	
		$this->prepareDeleteGetPatronCirculationCacheKey($session);
		
		$actual = $this->service->returnTitle($session, $itemId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method placeHold 
	* when called
	* should executesCorrectly
	*/
	public function test_placeHold_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
		$itemId = "aDummySession";
		$formatId = "aDummyFormatId";
		$email = "aDummyEmail";
		
		$this->overDriveSSMock->expects($this->once())
								->method("placeHold")
								->with($this->equalTo($session), $this->equalTo($itemId), $this->equalTo($formatId), $this->equalTo($email))
								->will($this->returnValue($expected));
		$this->prepareDeleteGetPatronCirculationCacheKey($session);
		
		$actual = $this->service->placeHold($session, $itemId, $formatId, $email);
		$this->assertEquals($expected, $actual);
	}

	/**
	* method cancelHold
	* when called
	* should executesCorrectly
	*/
	public function test_cancelHold_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
		$itemId = "aDummySession";
		$formatId = "aDummyFormatId";
		
		$this->overDriveSSMock->expects($this->once())
								->method("cancelHold")
								->with($this->equalTo($session), $this->equalTo($itemId), $this->equalTo($formatId))
								->will($this->returnValue($expected));
		$this->prepareDeleteGetPatronCirculationCacheKey($session);
		
		$actual = $this->service->cancelHold($session, $itemId, $formatId);
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
		$session = "aDummySession";
		$itemId = "aDummySession";
		
		$this->overDriveSSMock->expects($this->once())
								->method("addToWishList")
								->with($this->equalTo($session), $this->equalTo($itemId))
								->will($this->returnValue($expected));
		$this->prepareDeleteGetPatronCirculationCacheKey($session);
		
		$actual = $this->service->addToWishList($session, $itemId);
		$this->assertEquals($expected, $actual);
	}
	
		
	/**
	* method removeWishList 
	* when called
	* should executeSCorrectly
	*/
	public function test_removeWishList_called_executeSCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
		$itemId = "aDummyItemId";
		
		$this->overDriveSSMock->expects($this->once())
								->method("removeWishList")
								->with($this->equalTo($session), $this->equalTo($itemId))
								->will($this->returnValue($expected));
		$this->prepareDeleteGetPatronCirculationCacheKey($session);
		
		$actual = $this->service->removeWishList($session, $itemId);
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
		$session = "aDummySession";
		$ebookDays = "aDummyEbookDays";
		$audioBookDays = "aDummyAudioDays";
		$videoDays = "aDummyVideoDays";
		$disneyDays = "aDummyDisneyDays";
		
		$this->overDriveSSMock->expects($this->once())
								->method("changeLendingOptions")
								->with($this->equalTo($session), $this->equalTo($ebookDays), $this->equalTo($audioBookDays),
										$this->equalTo($videoDays), $this->equalTo($disneyDays))
								->will($this->returnValue($expected));
		$this->prepareDeleteGetPatronCirculationCacheKey($session);
		
		$actual = $this->service->changeLendingOptions($session, $ebookDays, $audioBookDays, $videoDays, $disneyDays);
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
		$session = "aDummySession";
		
		$this->overDriveSSMock->expects($this->once())
								->method("getLendingOptions")
								->with($this->equalTo($session))
								->will($this->returnValue($expected));
		
		$actual = $this->service->getLendingOptions($session);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method chooseFormat 
	* when called
	* should executesCorrectly
	*/
	public function test_chooseFormat_called_executesCorrectly()
	{
		$expected = "aDummyResult";
		$session = "aDummySession";
		$itemId = "aDummyItemId";
		$formatId = "aDummyFormatId";
		
		$this->overDriveSSMock->expects($this->once())
								->method("chooseFormat")
								->with($this->equalTo($session), $this->equalTo($itemId),  $this->equalTo($formatId))
								->will($this->returnValue($expected));
		
		$actual = $this->service->chooseFormat($session, $itemId, $formatId);
		$this->assertEquals($expected, $actual);
	}
	
	//Privates
	private function prepareDeleteGetPatronCirculationCacheKey($session)
	{
		$this->memcacheServicesMock->expects($this->once())
									->method("delete")
									->with($this->equalTo(OverDriveCacheSS::keyGetPatronCirculation.$session));
	}
		
}
?>