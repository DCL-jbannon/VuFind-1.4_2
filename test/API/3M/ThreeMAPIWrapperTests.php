<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/3M/ThreeMAPIWrapper.php';

class ThreeMAPIWrapperTests extends PHPUnit_Framework_TestCase
{
	const libraryId = "komf";
	const accesKey = "zJyecxf45LQjJelZ";
	const patronId = "23025006182976";
	
	//private $baseLibraryUrl = "http://localhost:9090/";
	private $baseLibraryUrl = "https://cloudlibraryapi.3m.com/cirrus/library/";
	private $service;
	private $itemId = "ff3r9";
	private $itemPlaceHoldId = "gn9r9";
	private $itemIdCanNOTCHECK = "uwfz9";
	
	public function setUp()
	{
		
		$this->baseLibraryUrl .= self::libraryId;
		$this->service = new ThreeMAPIWrapper($this->baseLibraryUrl, self::accesKey);
		parent::setUp();		
	}
	
	/**
	* method getItemDetails
	* when called
	* should executesCorrectly
	*/
	public function test_getItemDetails_called_executesCorrectly()
	{
		$expected = $itemId = $this->itemId;
		$actual = $this->service->getItemDetails($itemId);
		$this->assertEquals($expected, (string)$actual->ItemId);
	}
	
	/**
	 * method getItemDetails
	 * when novalidItem
	 * should returnEmpty
	 */
	public function test_getItemDetails_novalidItem_returnEmpty()
	{
		$itemId = "aa00b9";
		$actual = $this->service->getItemDetails($itemId);
		$this->assertEquals("Error", $actual->getName());
	}
	
	/**
	* method getItemsDetails
	* when called
	* should executesCorrectly
	*/
	public function test_getItemsDetails_called_executesCorrectly()
	{
		$expected[0] = $this->itemId;
		$expected[1] = "gn9r8";
		$actual = $this->service->getItemsDetails($expected[0].",".$expected[1]);
		$this->assertEquals($expected[0], (string)$actual->Item[0]->ItemId);
		$this->assertEquals($expected[1], (string)$actual->Item[1]->ItemId);
	}
	
	/**
	* method checkout
	* when canBeCheckOut
	* should executesCorrectly
	*/
	public function test_checkout_canBeCheckOut_executesCorrectly()
	{
		$expected = $this->itemId;
		$requestBody = $this->getBodyCheckOut($expected);
		
		$actual = $this->service->checkout($requestBody);
		$this->assertEquals($expected, (string)$actual->ItemId);
		$this->assertNotEmpty((string)$actual->DueDateInUTC);
	}
	
	/**
	 * method checkout
	 * when canNOTBeCheckOut
	 * should executesCorrectly
	 */
	public function test_checkout_canNOTBeCheckOut_executesCorrectly()
	{
		$expected = "Error";
		$requestBody = $this->getBodyCheckOut($this->itemIdCanNOTCHECK);
			
		$actual = $this->service->checkout($requestBody);
		$this->assertEquals($expected, (string)$actual->getName());
	}
	
	
	private function getBodyCheckOut($itemId)
	{
		$requestBody  = "<CheckoutRequest>";
		$requestBody .= "<ItemId>".$itemId."</ItemId>";
		$requestBody .= "<PatronId>".self::patronId."</PatronId>";
		$requestBody .= "</CheckoutRequest>";
		return $requestBody;
	}
	
	
	/**
	 * method checkin
	 * when canBeCheckIn
	 * should executesCorrectly
	 */
	public function test_checkin_canBeCheckIn_executesCorrectly()
	{
		$expected = 200;
	
		$requestBody  = "<CheckinRequest>";
		$requestBody .= "<ItemId>".$this->itemId."</ItemId>";
		$requestBody .= "<PatronId>".self::patronId."</PatronId>";
		$requestBody .= "</CheckinRequest>";
	
		$actual = $this->service->checkin($requestBody);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method checkin
	 * when canNOTBeCheckIn
	 * should executesCorrectly
	 */
	public function test_checkin_canNOTBeCheckIn_executesCorrectly()
	{
		$expected = 404;
	
		$requestBody  = "<CheckinRequest>";
		$requestBody .= "<ItemId>".$this->itemIdCanNOTCHECK."</ItemId>";
		$requestBody .= "<PatronId>".self::patronId."</PatronId>";
		$requestBody .= "</CheckinRequest>";
	
		$actual = $this->service->checkin($requestBody);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method placeHold 
	* when called
	* should executesCorrectly
	*/
	public function test_placeHold_called_executesCorrectly()
	{
		$expected = $this->itemPlaceHoldId;
		
		$requestBody  = "<PlaceHoldRequest>";
		$requestBody .= "<ItemId>".$this->itemPlaceHoldId."</ItemId>";
		$requestBody .= "<PatronId>".self::patronId."</PatronId>";
		$requestBody .= "</PlaceHoldRequest>";
		
		$actual = $this->service->placeHold($requestBody);
		$this->assertEquals($expected, (string)$actual->ItemId);
		$this->assertNotEmpty((string)$actual->AvailabilityDateInUTC);
	}
	
	/**
	 * method cancelHold
	 * when called
	 * should executesCorrectly
	 */
	public function test_cancelHold_called_executesCorrectly()
	{
		$expected = 200;
	
		$requestBody  = "<CancelHoldRequest>";
		$requestBody .= "<ItemId>".$this->itemPlaceHoldId."</ItemId>";
		$requestBody .= "<PatronId>".self::patronId."</PatronId>";
		$requestBody .= "</CancelHoldRequest>";
	
		$actual = $this->service->cancelHold($requestBody);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method getItemCirculation 
	* when called
	* should executesCorrectly
	*/
	public function test_getItemCirculation_called_executesCorrectly()
	{
		$expected = $this->itemId;
		$actual = $this->service->getItemCirculation($expected);
		$this->assertEquals($expected, (string)$actual->ItemId);
		$this->assertTrue(isset($actual->TotalCopies), "TotalCopies property");
		$this->assertTrue(isset($actual->AvailableCopies), "AvailableCopies property");
	}
	
	/**
	 * method getItemsCirculation
	 * when called
	 * should executesCorrectly
	 */
	public function test_getItemsCirculation_called_executesCorrectly()
	{
		$expected[0] = $this->itemPlaceHoldId;
		$expected[1] = $this->itemId;
		$actual = $this->service->getItemsCirculation($expected[0].",".$expected[1]);
		$this->assertEquals(2, $actual->count());
	}
	
	/**
	* method getPatronCirculation 
	* when called
	* should executesCorrectly
	*/
	public function test_getPatronCirculation_called_executesCorrectly()
	{
		$expected = self::patronId;
		$actual = $this->service->getPatronCirculation(self::patronId);
		$this->assertEquals($expected ,(string)$actual->PatronId);
	}	
	
}

?>