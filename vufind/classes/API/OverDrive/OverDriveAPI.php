<?php
require_once dirname(__FILE__).'/OverDriveCacheSS.php';
require_once dirname(__FILE__).'/OverDriveAPIWrapper.php';
require_once dirname(__FILE__).'/OverDriveAPIException.php';
require_once dirname(__FILE__).'/CollectionOverDriveIterator.php';

interface IOverDriveAPI{}

class OverDriveAPI implements IOverDriveAPI
{
	private $odw;
	private $clientKey;
	private $clientSecret;
	private $libraryId;
	
	public static $accessToken = NULL;
	private static $productsUrl = NULL;
	
	//SS
	public static $session = NULL;
	private $odss;
	
	public function __construct(IOverDriveAPIWrapper $overDriveWrapper = NULL, 
								IOverDriveSS $overDriveSS = NULL)
	{	
		global $configArray;
			
		if(!$overDriveWrapper) $overDriveWrapper = new OverDriveAPIWrapper();
		$this->odw = $overDriveWrapper;
		
		if(!$overDriveSS) $overDriveSS = new OverDriveCacheSS($configArray['OverDrive']['url'], 
				                                              $configArray['OverDrive']['theme'],
				                                              $configArray['OverDrive']['LibraryCardILS'], 
														      $configArray['OverDrive']['baseSecureUrl']);
		$this->odss = $overDriveSS;
		
		
		$this->clientKey = $configArray['OverDriveAPI']['clientKey'];
		$this->clientSecret = $configArray['OverDriveAPI']['clientSecret'];
		$this->libraryId = $configArray['OverDriveAPI']['libraryId'];
	}
	
	public function login()
	{
		$result = $this->odw->login($this->clientKey, $this->clientSecret);
		self::$accessToken = $result->access_token;
		return $result;
	}
	
	public function getInfoDCLLibrary()
	{
		$result = $this->odw->getInfoDCLLibrary(self::$accessToken, $this->libraryId);
		self::$productsUrl = $result->links->products->href;
		return $result;
	}
	
	public function getDigitalCollection($limit, $offset)
	{
		return  $this->odw->getDigitalCollection(self::$accessToken, self::$productsUrl, $limit, $offset);
	}
	
	public function getItemAvailability($itemId)
	{
		return  $this->odw->getItemAvailability(self::$accessToken, self::$productsUrl, $itemId);
	}
	
	public function getItemMetadata($itemId)
	{
		return  $this->odw->getItemMetadata(self::$accessToken, self::$productsUrl, $itemId);
	}
	
	//OVERDRIVE SCREEN SCRAPING
	public function getSession()
	{
		self::$session = $this->odss->getSession();
		return self::$session;
	}
	
	public function loginSS($username)
	{
		return $this->odss->login(self::$session, $username);
	}
	
	public function checkOut($itemId, $formatId)
	{
		return $this->odss->checkOut(self::$session, $itemId, $formatId);
	}
	
	public function placeHold($itemId, $formatId, $email)
	{
		return $this->odss->placeHold(self::$session, $itemId, $formatId, $email);
	}
	
	public function cancelHold($itemId, $formatId)
	{
		return $this->odss->cancelHold(self::$session, $itemId, $formatId);
	}
	
	public function addToWishList($itemId)
	{
		return $this->odss->addToWishList(self::$session, $itemId);
	}
	
	public function removeWishList($itemId)
	{
		return $this->odss->removeWishList(self::$session, $itemId);
	}
	
	public function changeLendingOptions($ebookDays, $audioBookDays, $videoDays, $disneyDays)
	{
		return $this->odss->changeLendingOptions(self::$session, $ebookDays, $audioBookDays, $videoDays, $disneyDays);
	}
	
	public function getLendingOptions()
	{
		return $this->odss->getLendingOptions(self::$session);
	}
	
	public function getItemDetails($itemId)
	{
		if(is_array($itemId))
		{
			return $this->odss->getMultipleItemsDetails(self::$session, $itemId);
		}
		return $this->odss->getItemDetails(self::$session, $itemId);
	}
	
	public function getPatronCirculation()
	{
		return $this->odss->getPatronCirculation(self::$session);
	}
	
	public function chooseFormat($itemId, $formatId)
	{
		return $this->odss->chooseFormat(self::$session, $itemId, $formatId);
	}
	
	
	/***************************/
	/**** SETTERS / GETTERS ****/
	/***************************/
	public function getAccessToken()
	{
		return self::$accessToken;
	}
	
	public function getProductsUrl()
	{
		return self::$productsUrl;
	}
}
?>