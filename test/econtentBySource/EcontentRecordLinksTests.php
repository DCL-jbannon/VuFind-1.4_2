<?php
require_once dirname(__FILE__).'/BaseHelperClassesTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/econtentBySource/EcontentRecordLinks.php';

class EcontentRecordLinksTests extends BaseHelperClassesTests
{

	const econtentRecordId = "aDummyEcontentRecordId";
	
	const accessUrl = "aDummyAccessUrl";
	const cancelHoldUrl = "aDummyCancelHoldUrl";
	const checkOutUrl = "aDummyCheckOutUrl";
	
	public function setUp()
	{
		parent::setUp();
		$this->service = new EcontentRecordLinks($this->econtentRecordDetailsMock);
	}
	
	/**
	* method getLinksItemChekedOut 
	* when returnGetAccessUrlsTypeIsString
	* should returnCorrectly
	*/
	public function atest_getLinksItemChekedOut_returnGetAccessUrlsTypeIsString_returnCorrectly()
	{
		$expected = $this->prepareGetCheckOutsLinks(self::accessUrl);
		
		$actual = $this->service->getLinksItemChekedOut();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getLinksItemChekedOut
	 * when returnGetAccessUrlsTypeIsArray
	 * should returnCorrectly
	 */
	public function test_getLinksItemChekedOuts_returnGetAccessUrlsTypeIsArray_returnCorrectly()
	{
		$expected = $this->prepareGetCheckOutsLinks(array(self::accessUrl));

		$actual = $this->service->getLinksItemChekedOut();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getCheckOutsLinks
	 * when returnLinkIsSetToFalse
	 * should returnCorrectly
	 */
	public function test_getLinksItemChekedOut_returnLinkIsSetToFalse_returnCorrectly()
	{
		$returnLink = false;
		$expected = $this->prepareGetCheckOutsLinks(array(self::accessUrl), $returnLink);
	
		$actual = $this->service->getLinksItemChekedOut(NULL, false);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method getCancelHoldsLinks 
	* when called
	* should executesCorrectly
	*/
	public function test_getCancelHoldsLinks_called_executesCorrectly()
	{
		$expected[] = array(
							'text' => 'Cancel&nbsp;Hold',
							'onclick' => "if (confirm('Are you sure you want to cancel this title?')){cancelEContentHold('".self::cancelHoldUrl."')};return false;",
						);
		
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getCancelHoldUrls")
										->will($this->returnValue(self::cancelHoldUrl));
		
		$actual = $this->service->getCancelHoldsLinks();
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method getLinksAvailableHolds 
	* when showCancelHoldButtonisTrue
	* should returnCorrectly
	*/
	public function test_getLinksAvailableHolds_showCancelHoldButtonisTrue_returnCorrectly()
	{
		
		$expected[] = array(
				'text' => 'Cancel&nbsp;Hold',
				'onclick' => "if (confirm('Are you sure you want to cancel this title?')){cancelEContentHold('".self::cancelHoldUrl."')};return false;",
		);
		$expected[] = array(
				'text' => 'Check Out',
				'url' => self::checkOutUrl,
		);
		
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("showCancelHoldLinkAvailableHolds")
										->will($this->returnValue(true));

		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getCancelHoldUrls")
										->will($this->returnValue(self::cancelHoldUrl));

		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getCheckOutUrls")
										->will($this->returnValue(self::checkOutUrl));
		
		$actual = $this->service->getLinksAvailableHolds();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getLinksAvailableHolds
	 * when showCancelHoldButtonisFalse
	 * should returnCorrectly
	 */
	public function test_getLinksAvailableHolds_showCancelHoldButtonisFalse_returnCorrectly()
	{
		$expected[] = array(
				'text' => 'Check Out',
				'url' => self::checkOutUrl,
		);
	
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("showCancelHoldLinkAvailableHolds")
										->will($this->returnValue(false));
	
		$this->econtentRecordDetailsMock->expects($this->never())
										->method("getCancelHoldUrls");
	
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getCheckOutUrls")
										->will($this->returnValue(self::checkOutUrl));
	
		$actual = $this->service->getLinksAvailableHolds();
		$this->assertEquals($expected, $actual);
	}
	
	//PRIVATES!!!
	private function prepareGetCheckOutsLinks($return, $returnLink = true)
	{
		
		$this->econtentRecordDetailsMock->expects($this->once())
										->method("getAccessUrls")
										->will($this->returnValue($return));
								
		$expected[0]['url'] = self::accessUrl;
		$expected[0]['text'] = "Access eContent";
		
		if ($returnLink)
		{
			$this->econtentRecordDetailsMock->expects($this->once())
											->method("getRecordId")
											->will($this->returnValue(self::econtentRecordId));
			
			$expected[1]['url'] = "#None";
			$expected[1]['text'] = "Return Now";
			$expected[1]['onclick'] = "if (confirm('Are you sure you want to return this title?')){returnEpub('/EcontentRecord/aDummyEcontentRecordId/ReturnTitle')};return false;";
		}
		
		return $expected;
	}
}
?>