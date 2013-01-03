<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/3M/ThreeMAPI.php';
require_once dirname(__FILE__).'/../../mother/ThreeMAPI/resultsMother.php';

class ThreeMAPITests extends PHPUnit_Framework_TestCase
{
	const itemId = "aDummyItemId";
	const patronId = "aDummtPatronId";
	private $threeMAPIWrapper;
	private $threeMMother;
	private $service;
		
	public function setUp()
	{
		$this->threeMMother = new ResultsThreeMAPIMother();
		
		$this->threeMAPIWrapper = $this->getMock("IThreeMAPIWrapper", array("getItemDetails","getItemsDetails", "checkout", "checkin", 
																			"placeHold", "cancelHold", "getItemCirculation", "getItemsCirculation",
																			"getPatronCirculation"));
		
		$this->service = new ThreeMAPI($this->threeMAPIWrapper);
		parent::setUp();		
	}

	
	/**
	* method getItemDetails 
	* when called
	* should executesCorrectly
	*/
	public function test_getItemDetails_called_executesCorrectly()
	{
		$expected = $this->threeMMother->getItemDetails();
		$this->prepareWrapperCallWithItemId("getItemDetails", $expected);
		
		$actual = $this->service->getItemDetails(self::itemId);
		$this->assertEquals($expected, $actual);
	}
	

	/**
	* method getItemsDetails 
	* when called
	* should executesCorrectly
	*/
	public function test_getItemsDetails_called_executesCorrectly()
	{
		$itemsId = "aDummyId1,aDummyId2,aDummyId3";
		$expected = $this->threeMMother->getItemsDetails();
		$this->prepareWrapperCallWithItemId("getItemsDetails", $expected, $itemsId);
		
		$actual = $this->service->getItemsDetails($itemsId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method checkout 
	* when cannotBeCheckedOut
	* should returnFalse
	*/
	public function test_checkout_cannotBeCheckedOut_returnFalse()
	{
		$result = $this->threeMMother->getErrorMessage();
		$this->prepareWrapperCallWithItemIdAndPatronId("checkout", $result);
		
		$actual = $this->service->checkout(self::itemId, self::patronId);
		$this->assertFalse($actual);
	}
	
	/**
	 * method checkout
	 * when canBeCheckedOut
	 * should returnResult
	 */
	public function test_checkout_canBeCheckedOutt_returnTrue()
	{
		$expected = $this->threeMMother->checkOutSuccessfull();
		$this->prepareWrapperCallWithItemIdAndPatronId("checkout", $expected);
				
		$actual = $this->service->checkout(self::itemId, self::patronId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method checkin 
	* when cannotBeCheckedIn
	* should returnFalse
	*/
	public function test_checkin_cannotCheckIn_returnFalse()
	{
		$result = "aDummyValueDiferentThan200";
		$this->prepareWrapperCallWithItemIdAndPatronId("checkin", $result);
				
		$actual = $this->service->checkin(self::itemId, self::patronId);
		$this->assertFalse($actual);
	}
	
	/**
	 * method checkin
	 * when canBeCheckedIn
	 * should returnTrue
	 */
	public function test_checkin_canBeCheckedIn_returnTrue()
	{
		$result = "200";
		$this->prepareWrapperCallWithItemIdAndPatronId("checkin", $result);
		
		$actual = $this->service->checkin(self::itemId, self::patronId);
		$this->assertTrue($actual);
	}
	
	/**
	 * method placeHold
	 * when cannotPlaceHold
	 * should returnFalse
	 */
	public function test_placeHold_cannotPlaceHold_returnFalse()
	{
		$result = $this->threeMMother->getErrorMessage();
		$this->prepareWrapperCallWithItemIdAndPatronId("placeHold", $result);
				
		$actual = $this->service->placeHold(self::itemId, self::patronId);
		$this->assertFalse($actual);
	}
	
	/**
	 * method placeHold
	 * when canPlaceHold
	 * should returnResult
	 */
	public function test_placeHold_canPlaceHold_returnFalse()
	{
		$expected = $this->threeMMother->placeHoldSuccessfull();
		$this->prepareWrapperCallWithItemIdAndPatronId("placeHold", $expected);
			
		$actual = $this->service->placeHold(self::itemId, self::patronId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method cancelHold
	 * when cannotBeCancelHold
	 * should returnFalse
	 */
	public function test_cancelHold_cannotBeCancelHold_returnFalse()
	{
		$result = "aDummyValueDiferentThan200";
		$this->prepareWrapperCallWithItemIdAndPatronId("cancelHold", $result);
		
		$actual = $this->service->cancelHold(self::itemId, self::patronId);
		$this->assertFalse($actual);
	}
	
	/**
	 * method cancelHold
	 * when canBeCheckedIn
	 * should returnTrue
	 */
	public function test_cancelHold_canBeCanCancelHold_returnTrue()
	{
		$result = "200";
		$this->prepareWrapperCallWithItemIdAndPatronId("cancelHold", $result);
		
		$actual = $this->service->cancelHold(self::itemId, self::patronId);
		$this->assertTrue($actual);
	}
	
	
	/**
	 * method getItemCirculation
	 * when itemIdDoesNotExists
	 * should returnResults
	 */
	public function test_getItemCirculation_itemIdDoesNotExists_returnResults()
	{
		$result = $this->threeMMother->getErrorMessage();
		$this->prepareWrapperCallWithItemId("getItemCirculation", $result);
	
		$actual = $this->service->getItemCirculation(self::itemId);
		$this->assertFalse($actual);
	}
	
	/**
	* method getItemCirculation 
	* when itemIdExists
	* should returnResults
	*/
	public function test_getItemCirculation_itemIdExists_returnResults()
	{
		$expected = $this->threeMMother->getItemCirculationResult();
		$this->prepareWrapperCallWithItemId("getItemCirculation", $expected);
		
		$actual = $this->service->getItemCirculation(self::itemId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getItemsCirculation
	 * when itemIdDoesNotExists
	 * should returnFalse
	 */
	public function test_getItemsCirculation_itemIdDoesNotExists_returnFalse()
	{
		$itemsId = "aDummyItemId1,aDummyItemId2";
		$result = $this->threeMMother->getErrorMessage();
		$this->prepareWrapperCallWithItemId("getItemsCirculation", $result, $itemsId);
	
		$actual = $this->service->getItemsCirculation($itemsId);
		$this->assertFalse($actual);
	}
	
	/**
	 * method getItemsCirculation
	 * when itemIdExists
	 * should returnResults
	 */
	public function test_getItemsCirculation_itemIdExists_returnResults()
	{
		$itemsId = "aDummyItemId1,aDummyItemId2";
		$expected = $this->threeMMother->getItemsCirculationResult();
		$this->prepareWrapperCallWithItemId("getItemsCirculation", $expected, $itemsId);
	
		$actual = $this->service->getItemsCirculation($itemsId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getPatronCirculation 
	* when called	
	* should returnResult
	*/
	public function test_getPatronCirculation_PatronIdIsNotValid_returnFalse()
	{
		$expected = $this->threeMMother->getPatronCirculationResults();
		$this->threeMAPIWrapper->expects($this->once())
								->method("getPatronCirculation")
								->with($this->equalTo(self::patronId))
								->will($this->returnValue($expected));
		$actual = $this->service->getPatronCirculation(self::patronId);
		$this->assertEquals($expected, $actual);
	}
	
	//prepares
	private function prepareWrapperCallWithItemId($methodName, $result, $parameter = NULL)
	{
		if($parameter === NULL)
		{
			$parameter = self::itemId;
		}
		
		$this->threeMAPIWrapper->expects($this->once())
								->method($methodName)
								->with($this->equalTo($parameter))
								->will($this->returnValue($result));
	}
	
	private function prepareWrapperCallWithItemIdAndPatronId($methodName, $result)
	{
		$this->threeMAPIWrapper->expects($this->once())
								->method($methodName)
								->with($this->equalTo(self::itemId), $this->equalTo(self::patronId))
								->will($this->returnValue($result));
	}
}
?>