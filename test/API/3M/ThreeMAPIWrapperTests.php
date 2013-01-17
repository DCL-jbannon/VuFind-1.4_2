<?php
//http://ebook.3m.com/library/DouglasCountyLibraries-query-go-not_in_stock_items-exclude-physical_items-exclude-not_loanable_items-include-sort-year_of_publication-page-1-search/
require_once dirname(__FILE__).'/../../../vufind/classes/API/3M/ThreeMAPIWrapper.php';

class ThreeMAPIWrapperTests extends PHPUnit_Framework_TestCase
{
	const libraryId = "komf";
	const accesKey = "zJyecxf45LQjJelZ";
	const patronId = "23025006182976";
	
	//private $base3MUrl = "http://localhost:9090";
	private $base3MUrl = "https://cloudlibraryapi.3m.com";
	private $service;
	private $itemId = "2uv89";
	private $itemPlaceHoldId = "ayryg9";
	private $itemIdCanNOTCHECK = "uwfz9";
	
	public function setUp()
	{
		global $configArray;
		$configArray['3MAPI']['url'] = $this->base3MUrl;
		$configArray['3MAPI']['libraryId'] = self::libraryId;
		$configArray['3MAPI']['accesKey'] = self::accesKey;
		$this->service = new ThreeMAPIWrapper();
		parent::setUp();		
	}
	
	/**
	* method getBaseUriPath 
	* when called
	* should returnCorrectString
	*/
	public function test_getBaseUriPath_called_returnCorrectString()
	{
		$expected = ThreeMAPIWrapper::baseUriPath.self::libraryId;
		$actual = $this->service->getBaseUriPath();
		$this->assertEquals($expected, $actual);
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
		$expected[1] = $this->itemPlaceHoldId;
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
		$actual = $this->service->checkout($this->itemId, self::patronId);
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
		$actual = $this->service->checkout($this->itemIdCanNOTCHECK, self::patronId);
		$this->assertEquals($expected, (string)$actual->getName());
	}
	
	
	/**
	 * method checkin
	 * when canBeCheckIn
	 * should executesCorrectly
	 */
	public function test_checkin_canBeCheckIn_executesCorrectly()
	{
		$expected = 200;	
		$actual = $this->service->checkin($this->itemId, self::patronId);
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
		$actual = $this->service->checkin($this->itemId, self::patronId);
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
		
		$actual = $this->service->placeHold($this->itemPlaceHoldId, self::patronId);
		$this->assertEquals($expected, (string)$actual->ItemId);
		$this->assertNotEmpty((string)$actual->AvailabilityDateInUTC);
	}
	
	/**
	 * method placeHold
	 * when calledAgain
	 * should returnError
	 */
	public function test_placeHold_calledAgain_returnError()
	{
		$expected = "Error";
		$actual = $this->service->placeHold($this->itemPlaceHoldId, self::patronId);
		$this->assertEquals($expected, (string)$actual->getName());
	}
	
	/**
	 * method cancelHold
	 * when called
	 * should executesCorrectly
	 */
	public function test_cancelHold_called_executesCorrectly()
	{
		$expected = 200;	
		$actual = $this->service->cancelHold($this->itemPlaceHoldId, self::patronId);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getItemCirculation
	 * when doesNotExists
	 * should returnErrorMessage
	 */
	public function test_getItemCirculationa_doesNotExists_executesCorrectly()
	{
		$expected = "Error";
		$actual = $this->service->getItemCirculation("IdDoesNotExists");
		$this->assertEquals($expected, (string)$actual->getName());
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
	 * when oneOrMoreItemIdIsNotValid
	 * should executesCorrectly
	 */
	public function test_getItemsCirculation_oneOrMoreItemIdIsNotValid_executesCorrectly()
	{
		$expected = "Error";
		$actual = $this->service->getItemsCirculation($this->itemPlaceHoldId.",ThisItemIdIsNotValid");
		$this->assertEquals($expected, (string)$actual->getName());
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
	
	/**
	 * method getPatronCirculation
	 * when wrongPatronId
	 * should executesCorrectly
	 */
	public function test_getPatronCirculation_wrongPatronId_executesCorrectly()
	{
		$expected = $patronId = "aNonValidPatronId";
		$actual = $this->service->getPatronCirculation($patronId);
		$this->assertEquals($expected ,(string)$actual->PatronId);//Return Empty Lists, but Do not give a error message! :(
	}	
}
?>