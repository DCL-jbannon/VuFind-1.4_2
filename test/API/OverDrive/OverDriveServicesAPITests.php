<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveServicesAPI.php';

class OverDriveServicesAPITests extends PHPUnit_Framework_TestCase
{
	const clientKey = "aDummyClientKey";
	const clientSecret = "aDummyClientSecret";
	const libraryId = 1344;
	private $service;
	private $overDriveAPIMock;
	
	public function setUp()
	{
		$this->overDriveAPIMock = $this->getMock("IOverDriveAPI",array("getItemAvailability","login","getAccessToken","getItemMetadata","getInfoDCLLibrary"));
		$this->service = new OverDriveServicesAPI(self::clientKey, self::clientSecret, self::libraryId, $this->overDriveAPIMock);
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
		$this->overDriveAPIMock->expects($this->once())
								->method("getAccessToken")
								->will($this->returnValue(""));
	
		$this->overDriveAPIMock->expects($this->once())
								->method("login");
		
		$this->overDriveAPIMock->expects($this->once())
							   ->method("getInfoDCLLibrary");
	
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
						array("getItemMetadata"),
						array("getItemAvailability")
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
		$this->overDriveAPIMock->expects($this->once())
								->method("getAccessToken")
								->will($this->returnValue("aDummyNonValidToken"));
	
		$this->overDriveAPIMock->expects($this->once())
								->method("login");
		
		$this->overDriveAPIMock->expects($this->once())
								->method("getInfoDCLLibrary");
	
		$this->overDriveAPIMock->expects($this->at(1))
								->method($methodToTest)
								->with($this->equalTo($itemId))
								->will($this->throwException(new OverDriveTokenExpiredException()));
		/**
		 * http://bit.ly/VjFlHJ
		 * Note that the counter is per-mock across all method calls received to it. Thus if there are going to be two intervening calls to $pdo, you would use 0 and 3
		 */
		$this->overDriveAPIMock->expects($this->at(4))
								->method($methodToTest)
								->with($this->equalTo($itemId))
								->will($this->returnValue($expected));
	
		$actual = $this->service->$methodToTest($itemId);
		$this->assertEquals($expected, $actual);
	
	}
	
	
}

?>