<?php
require_once dirname(__FILE__).'/BaseHelperClassesTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/econtentBySource/EcontentRecordShowButtons.php';

class EcontentRecordShowButtonsTests extends BaseHelperClassesTests
{

	public function setUp()
	{
		parent::setUp();
		$this->service = new EcontentRecordShowButtons($this->econtentRecordDetailsMock);		
	}
	
	/**
	 * method showCheckOut
	 * when cannotBeCheckedOut
	 * should returnFalse
	 * @dataProvider DP_showCheckOut_cannotBeCheckedOut
	 */
	public function test_showCheckOut_cannotBeCheckedOut_returnFalse($user)
	{
		$this->prepareisCheckOutAvailable(false);
		$actual = $this->service->showCheckOut($user);
		$this->assertFalse($actual);
	}
	
	public function DP_showCheckOut_cannotBeCheckedOut()
	{
		return array(
				array(NULL), //No Patron
				array($this->userMock)
		);
	}
	
	/**
	* method showCheckOut 
	* when UserIsNullCanBeCheckOut
	* should returnTrue
	*/
	public function test_showCheckOut_UserIsNullCanBeCheckOut_returnTrue()
	{
		$this->prepareisCheckOutAvailable(true);
		$actual = $this->service->showCheckOut();
		$this->assertTrue($actual);
	}
	
	/**
	 * method showCheckOut
	 * when UserIsNullCanNotBeCheckOut
	 * should returnTrue
	 */
	public function test_showCheckOut_UserIdIsNullCanNotBeCheckOut_returnTrue()
	{
		$this->prepareisCheckOutAvailable(false);	
		$actual = $this->service->showCheckOut();
		$this->assertFalse($actual);
	}
	
	/**
	* method showCheckOut 
	* when UserIsNotNullAndAvailableToCheckOutAndUserHasNotCheckedItOut
	* should returnTrue
	*/
	public function test_showCheckOut_UserIsNotNullAndAvailableToCheckOutAndUserHasNotCheckedItOut_returnTrue()
	{
		$this->prepareisCheckOutAvailable(true);
		$this->prepareCheckedOutByPatron(false);
		
		$actual = $this->service->showCheckOut($this->userMock);
		$this->assertTrue($actual);
	}
	
	/**
	 * method showCheckOut
	 * when UserIsNotNullAndAvailableToCheckOutAndUserHasCheckedItOut
	 * should returnFalse
	 */
	public function test_showCheckOut_UserIsNotNullAndAvailableToCheckOutAndUserHasNotCheckedItOut_returnFalse()
	{
		$patronID = "aDummyPatronId";
		$this->prepareisCheckOutAvailable(true);
		$this->prepareCheckedOutByPatron(true);
	
		$actual = $this->service->showCheckOut($this->userMock);
		$this->assertFalse($actual);
	}
	
	/**
	* method showPlaceHold 
	* when cannotPlaceHold
	* should returnFalse
	*/
	public function test_showPlaceHold_cannotPlaceHold_returnFalse()
	{
		$this->prepareIsPlaceHoldAvailable(false);
		$actual = $this->service->showPlaceHold();
		$this->assertFalse($actual);
	}
	
	/**
	* method showPlaceHold 
	* when patronIdIsNullCanPlaceHold
	* should returnTrue
	*/
	public function test_showPlaceHold_patronIdIsNullCanPlaceHold_returnTrue()
	{
		$this->prepareIsPlaceHoldAvailable(true);
		$actual = $this->service->showPlaceHold();
		$this->assertTrue($actual);
	}
	
	/**
	 * method showPlaceHold
	 * when UserIsNotNullCanPlaceHoldItemHasNotCheckedOutByPatron
	 * should returnTrue
	 */
	public function test_showPlaceHold_UserIdIsNotNullCanPlaceHoldItemHasNotCheckedOutByPatron_returnTrue()
	{
		$patronId = "aDummyPatronId";
		$this->prepareIsPlaceHoldAvailable(true);
		$this->prepareCheckedOutByPatron(false);
		
		$actual = $this->service->showPlaceHold($this->userMock);
		$this->assertTrue($actual);
	}
	
	/**
	 * method showPlaceHold
	 * when UserIsNotNullCanPlaceHoldItemHasBeenCheckedOutByPatron
	 * should returnTrue
	 */
	public function test_showPlaceHold_UserIsNotNullCanPlaceHoldItemHasBeenCheckedOutByPatron_returnTrue()
	{
		$patronId = "aDummyPatronId";
		$this->prepareIsPlaceHoldAvailable(true);
		$this->prepareCheckedOutByPatron(true);
	
		$actual = $this->service->showPlaceHold($this->userMock);
		$this->assertFalse($actual);
	}
	
	
	/**
	* method showAddToWishList 
	* when AddToWishListIsNotAvailable
	* should returnFalse
	*/
	public function test_showAddToWishList_AddToWishListIsNotAvailable_returnTrue()
	{
		$this->prepareisAddToWishListAvailable(false);
		$actual = $this->service->showAddToWishList();
		$this->assertFalse($actual);
	}
	
	/**
	* method showAddToWishList 
	* when IsAvailableCannotCheckedOutCannotPlaceHolds
	* should returnTrue
	*/
	public function test_showAddToWishList_IsAvailableCannotCheckedOutCannotPlaceHolds_returnTrue()
	{
		$this->prepareisAddToWishListAvailable(true);
		$this->prepareisCheckOutAvailable(false);
		$this->prepareIsPlaceHoldAvailable(false);
				
		$actual = $this->service->showAddToWishList();
		$this->assertTrue($actual);
	}
	
	/**
	* method showAddToWishList 
	* when isCheckedOutByPatron
	* should returnFalse
	*/
	public function test_showAddToWishList_isCheckedOutByPatron_returnFalse()
	{
		$patronId = "aDummyPatronId";
		$this->prepareisAddToWishListAvailable(true);
		$this->prepareCheckedOutByPatron(true);
		
		$actual = $this->service->showAddToWishList($this->userMock);
		$this->assertFalse($actual);
	}
	
	/**
	 * method showAddToWishList
	 * when isPlaceHoldByPatron
	 * should returnFalse
	 */
	public function test_showAddToWishList_isPlaceHoldByPatron_returnFalse()
	{
		$patronId = "aDummyPatronId";
		$this->prepareisAddToWishListAvailable(true);
		$this->prepareCheckedOutByPatron(false);
		$this->prepareIsCancelHoldAvailable(true);	
										
		$actual = $this->service->showAddToWishList($this->userMock);
		$this->assertFalse($actual);
	}
	
	
	/**
	* method showAccessOnline 
	* when called
	* should executesCorrectly
	*/
	public function test_showAccessOnline_called_executesCorrectly()
	{
		$expected = "aDummyBooleanResult";
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("isAccessOnlineAvailable")
										->will($this->returnValue($expected));
		
		$actual = $this->service->showAccessOnline();
		$this->assertEquals($expected, $actual);
	}
	
		
	
	//PREPARES
	private function prepareCheckedOutByPatron($result)
	{
		$this->econtentRecordDetailsMock->expects($this->once())
											->method("isCheckedOutByPatron")
											->will($this->returnValue($result));
	}
	
	private function prepareisAddToWishListAvailable($result)
	{
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("isAddWishListAvailable")
										->will($this->returnValue($result));
	}
	
	private function prepareisCheckOutAvailable($result)
	{
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("isCheckOutAvailable")
										->will($this->returnValue($result));
	}
	
	private function prepareIsCancelHoldAvailable($result)
	{
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("isCancelHoldAvailable")
										->will($this->returnValue($result));
	}
	
	private function prepareIsPlaceHoldAvailable($result)
	{
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("isPlaceHoldAvailable")
										->will($this->returnValue($result));
	}
		
}
?>