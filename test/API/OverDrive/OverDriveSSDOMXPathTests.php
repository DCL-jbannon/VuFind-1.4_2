<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveSSDOMXPath.php';

class OverDriveSSDOMXPathTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
		
	public function setUp()
	{
		$this->service = new OverDriveSSDOMXPath();
		parent::setUp();		
	}
	
	/**
	* method getUrlHTML302 
	* when called
	* should returnCorrectly
	*/
	public function test_getUrlHTML302_called_returnCorrectly()
	{
		$expected = "http://www.emedia2go.org/5344CC55-B2F2-4AEE-BFC4-FE2C058E93F9/10/50/en/Default.htm";
		$source = "<html><head><title>Object moved</title></head><body><h2>Object moved to <a href=\"http://www.emedia2go.org/5344CC55-B2F2-4AEE-BFC4-FE2C058E93F9/10/50/en/Default.htm\">here</a>.</h2></body></html>";
		
		$actual = $this->service->getUrlHTML302($source);
		$this->assertEquals($expected, $actual); 	
	}
	
	/**
	 * 
	 * method getUrlHTML302 
	 * when sourceStructureNotValid
	 * should returnEmpty
	*/
	public function test_getUrlHTML302_sourceStructureNotValid_returnEmpty()
	{
		$expected = "";
		$source = "<html><head><title>System Error</title></head><body><h1>System Error</h1><p>The following system error occurred...</p><table><tr></body></html>";
		
		$actual = $this->service->getUrlHTML302($source);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getTransactionIdByItemID
	 * when notFound
	 * should returnEmpty
	 */
	public function test_getTransactionIdByItemID_notFound_returnEmpty()
	{
		$expected = "";
		$itemId = "aNonValidItemId";
		$source = file_get_contents(dirname(__FILE__).'/../../testFiles/OverDriveSS/myAccountPage.html');
	
		$actual = $this->service->getTransactionIdByItemID($source, $itemId);
		$this->assertEquals($expected, $actual);
	}

	/**
	* method getTransactionIdByItemID 
	* when called
	* should returnCorrectly
	* @dataProvider DP_getTransactionIdByItemID
	*/
	public function test_getTransactionIdByItemID_called_returnCorrectly($itemId)
	{
		$expected = "269-802406-00066";
		$source = file_get_contents(dirname(__FILE__).'/../../testFiles/OverDriveSS/myAccountPage.html');
		
		$actual = $this->service->getTransactionIdByItemID($source, $itemId);
		$this->assertEquals($expected, $actual);
	}
	
	public static function DP_getTransactionIdByItemID() 
	{
		return array(
						array("5191e036-db83-4e74-a719-12472b8ada8a"),
						array("5191E036-DB83-4E74-A719-12472B8ADA8A"),
					);
	}
	
	/**
	 * method getLendingOptionsValue
	 * when called
	 * should returnCorrectly
	 */
	public function test_getLendingOptionsValue_called_returnCorrectly()
	{
		$expected = new stdClass();
		$expected->ebook = 14;
		$expected->audio = 7;
		$expected->video = 21;
		$expected->disney = 14;
		
		$source = file_get_contents(dirname(__FILE__).'/../../testFiles/OverDriveSS/lendingPage.html');
	
		$actual = $this->service->getLendingOptionsValue($source);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getItemDetails 
	* when canBeBorrow
	* should executeCorrectly
	*/
	public function test_getItemDetails_canBeBorrow_executeCorrectly()
	{
		$itemId = "61258FB3-BBB7-4F78-8D7D-19E7621EE6F0";
		$expected = new stdClass();
		$expected->AvailableCopies = 2;
		$expected->TotalCopies = 6;
		$expected->OnHoldCount = 8;
		$expected->CanCheckout = true;
		$expected->CanHold = false;
		$expected->CanAddWishList = true;
		
		$source = file_get_contents(dirname(__FILE__).'/../../testFiles/OverDriveSS/itemDetailPageCanBeBorrow.html');
		
		$actual = $this->service->getItemDetails($source, $itemId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getItemDetails
	 * when canBePlaceHold
	 * should executeCorrectly
	 */
	public function test_getItemDetails_canBePlaceHold_executeCorrectly()
	{
		$itemId = "82CDD641-857A-45CA-8775-34EEDE35B238";
		$expected = new stdClass();
		$expected->AvailableCopies = 0;
		$expected->TotalCopies = 1;
		$expected->OnHoldCount = 0;
		$expected->CanCheckout = false;
		$expected->CanHold = true;
		$expected->CanAddWishList = true;
		$expected->formatIdHold = 25;
		$source = file_get_contents(dirname(__FILE__).'/../../testFiles/OverDriveSS/itemDetailPageCanBePlaceHold.html');
	
		$actual = $this->service->getItemDetails($source, $itemId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getPatronCirculation 
	* when called
	* should executesCorrectly
	*/
	public function test_getPatronCirculation_called_executesCorrectly()
	{
		$baseUrl = "https://secure38.libraryreserve.com/www.emedia2go.org/4E9EE99C-03A2-4693-B244-ACE4CA9A66E4/10/50/en/";
		$expected = new stdClass();
		
		$expected->Checkouts[] = array("ItemId" =>  "FBB87483-E23A-4B7C-9351-70460B801DF8",
									   "Title"=>    "The Murder Book",
									   "Expires" => "Mar 20 2013  3:44PM",
									   "Link" => $baseUrl."BANGPurchase.dll?Action=Download&ReserveID=fbb87483-e23a-4b7c-9351-70460b801df8&FormatID=50",
									   "OverDriveReadLink" => false,
									   "ChooseFormat"=>false);
		
		$expected->Checkouts[] = array("ItemId" =>  "8EF8AECF-EDCE-43AD-A23E-3A8BB8D7BCB1", 
									   "Title"=>    "2011 Baby Names Almanac", 
				                       "Expires" => "Mar 27 2013 10:28AM",
									   "Link" =>        $baseUrl."BANGPurchase.dll?Action=Download&ReserveID=8ef8aecf-edce-43ad-a23e-3a8bb8d7bcb1&FormatID=420&url=blabla.html",
									   "OverDriveReadLink" => $baseUrl."BANGPurchase.dll?Action=Download&ReserveID=8ef8aecf-edce-43ad-a23e-3a8bb8d7bcb1&FormatID=610&url=blabla.html",
									   "ChooseFormat"=>false);
		
		$expected->Checkouts[] = array("ItemId" =>  "E90724B0-EFED-4FD2-AB42-113EC41B78CD", 
				                       "Title"=>    "A Dog Is a Dog", 
				                       "Expires" => "Mar 26 2013  6:12PM",
									   "Link" => $baseUrl."BANGPurchase.dll?Action=Download&ReserveID=e90724b0-efed-4fd2-ab42-113ec41b78cd&FormatID=420",
									   "OverDriveReadLink" => $baseUrl."BANGPurchase.dll?Action=Download&ReserveID=e90724b0-efed-4fd2-ab42-113ec41b78cd&FormatID=610",
									   "ChooseFormat"=>false);
		
		$expected->Checkouts[] = array("ItemId" =>  "5191E036-DB83-4E74-A719-12472B8ADA8A",
									   "Title"=>    "How to Raise the Perfect Dog",
									   "Expires" => "Mar 26 2013  5:17PM",
									   "Link" => $baseUrl."BANGPurchase.dll?Action=Download&ReserveID=5191e036-db83-4e74-a719-12472b8ada8a&FormatID=420",
									   "OverDriveReadLink" => false,
									   "ChooseFormat"=>false);

		$expected->Holds[] = array("ItemId" => "67AAFEF1-3292-4DC0-B6C5-0D35F466C511",
								   "FormatId" => "420",
								   "Title" =>  "Fifty Shades Freed",
								   "UserPosition" =>  50,
								   "QueueLength" => 50);
		
		$expected->Holds[] = array("ItemId" => "A499D997-7AAC-4A80-803D-C28D9BAF1004",
								   "FormatId" => "25",
								   "Title" =>  "Life of Pi",
								   "UserPosition" =>  34,
								   "QueueLength" =>  35);
		
		
		$expected->AvailableHolds[] = array("ItemId" => "82CDD641-857A-45CA-8775-34EEDE35B238",
											"Title" =>  "Fifty Shades of Grey");
		
		$expected->WishList[] = array("ItemId" => "55B85A07-7728-40CC-A18B-BBB05F39CAEE",
								      "Title" =>  "Fancy Nancy");
		
		$expected->WishList[] = array("ItemId" => "08BEDB15-62F3-4719-9671-92EB298EA540",
								      "Title" =>  "The Ultimate Harry Potter and Philosophy");
		$source = file_get_contents(dirname(__FILE__).'/../../testFiles/OverDriveSS/myAccountHoldBorrowOption.html');
		
		$actual = $this->service->getPatronCirculation($source, $baseUrl);
		$this->assertEquals($expected, $actual);
		//print_r($actual);
	}
	
	
	/**
	 * method getPatronCirculation
	 * when itemCheckedOutNotDownloaded
	 * should executesCorrectly
	 */
	public function test_getPatronCirculation_itemCheckedOutNotDownloaded_executesCorrectly()
	{
		$baseUrl = "https://secure38.libraryreserve.com/www.emedia2go.org/4E9EE99C-03A2-4693-B244-ACE4CA9A66E4/10/50/en/";
		$expected = new stdClass();
	
		$expected->Checkouts[] = array("ItemId" =>  "1122A071-CDBF-4A8B-839D-451BCA0DB929",
									   "Title"=>    "The Story of Christmas",
									   "Expires" => "Mar 29 2013  4:28PM",
									   "Link" => "",
				                       "OverDriveReadLink" => "",
									   "ChooseFormat" => true);
		$expected->Holds = array();
		$expected->AvailableHolds = array();
		$expected->WishList = array();
		
		$source = file_get_contents(dirname(__FILE__).'/../../testFiles/OverDriveSS/myAccountItemCheckedNotDownloadedYet.html');
		$actual = $this->service->getPatronCirculation($source, $baseUrl);
		$this->assertEquals($expected, $actual);	
	}

	/**
	 * method getPatronCirculation
	 * when nothing
	 * should executesCorrectly
	 */
	public function test_getPatronCirculation_nothing_executesCorrectly()
	{
		$baseUrl = "https://secure38.libraryreserve.com/www.emedia2go.org/4E9EE99C-03A2-4693-B244-ACE4CA9A66E4/10/50/en/";
		$expected = new stdClass();
		$expected->Checkouts = array();
		$expected->Holds = array();
		$expected->WishList = array();
		$expected->AvailableHolds = array();
		$source = file_get_contents(dirname(__FILE__).'/../../testFiles/OverDriveSS/myAccountNoCheckOutHoldsWishList.html');
		
		$actual = $this->service->getPatronCirculation($source, $baseUrl);
		$this->assertEquals($expected, $actual);
	}
	
}
?>