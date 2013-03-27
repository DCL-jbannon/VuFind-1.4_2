<?php
require_once dirname(__FILE__).'/OverDriveSSDOMXPath.php';
require_once dirname(__FILE__).'/OverDriveSSUtils.php';
require_once dirname(__FILE__).'/../../../web/sys/Logger.php';

interface IOverDriveSS
{
	public function getSession($username = NULL);
	public function login($session, $username);
	public function checkOut($session, $itemId, $formatId, $download = true);
	public function returnTitle($session, $itemId);
	public function placeHold($session, $itemId, $formatId, $email);
	public function cancelHold($session, $itemId, $formatId);
	public function addToWishList($session, $itemId);
	public function removeWishList($session, $itemId);
	public function changeLendingOptions($session, $ebookDays, $audioBookDays, $videoDays, $disneyDays);
	public function getLendingOptions($session);
	public function getItemDetails($session, $itemId);
	public function getMultipleItemsDetails($session, $itemsId);
	public function getPatronCirculation($session);
	public function chooseFormat($session, $itemId, $formatId);
	
}

class OverDriveSS implements IOverDriveSS
{
	private $odDOM;
	private $odUtils;
	private $errorCode;
	private $errorMessage;
	private $baseUrl;
	private $baseServerName;
	private $baseSecureUrl;
	private $theme;
	private $libraryCardILS;
	private $ch;
	private $logger;
	
	private $userAgent = "Douglas County Libraries OverDrive Screen Scraping Version 1.0";
	private $loginUrl = "/{SESSIONID}{theme}BANGAuthenticate.dll"; //Secure URL
	private $downloadUrl = "/{SESSIONID}{theme}BANGPurchase.dll?Action=Download&ReserveID={itemID}&FormatID={formatId}"; //Secure URL
	private $earlyReturnUrl = "/{SESSIONID}{theme}BANGPurchase.dll?Action=EarlyReturn&TransactionID={transID}&ReserveID={itemID}&URL=MyAccount.htm";
	private $myAccountUrl = "/{SESSIONID}{theme}MyAccount.htm?PerPage=80";
	private $placeHoldUrl = "/{SESSIONID}{theme}BANGAuthenticate.dll?Action=LibraryWaitingList";
	private $cancelHoldUrl = "/{SESSIONID}{theme}BANGAuthenticate.dll?Action=RemoveFromWaitingList&id={itemID}&format={formatId}&url=waitinglistremove.htm";
	private $addToWishListUrl = "/{SESSIONID}{theme}BANGCart.dll?Action=WishListAdd&ID={itemID}";
	private $removeWishListUrl = "/{SESSIONID}{theme}BANGCart.dll?Action=WishListRemove&ID={itemID}";
	private $lendingOptionsUrl = "/{SESSIONID}{theme}BANGAuthenticate.dll?Action=EditUserLendingPeriodsFormatClass";
	private $itemDetailsUrl = "/{SESSIONID}{theme}ContentDetails.htm?id={itemID}";
	
	public function __construct($baseUrl, $theme, $libraryCardILS, $baseSecureUrl)
	{
		
		$this->baseServerName = $baseUrl;
		$this->baseUrl = "http://".$baseUrl;
		$this->theme = $theme;
		$this->libraryCardILS = $libraryCardILS;
		$this->baseSecureUrl = $baseSecureUrl.$baseUrl;

		$this->odDOM = new OverDriveSSDOMXPath();
		$this->odUtils = new OverDriveSSUtils();
		
		$this->loginUrl = str_replace("{theme}", $this->theme, $this->loginUrl);
		$this->downloadUrl = str_replace("{theme}", $this->theme, $this->downloadUrl);
		$this->earlyReturnUrl = str_replace("{theme}", $this->theme, $this->earlyReturnUrl);
		$this->myAccountUrl = str_replace("{theme}", $this->theme, $this->myAccountUrl);
		$this->placeHoldUrl = str_replace("{theme}", $this->theme, $this->placeHoldUrl);
		$this->cancelHoldUrl = str_replace("{theme}", $this->theme, $this->cancelHoldUrl);
		$this->addToWishListUrl = str_replace("{theme}", $this->theme, $this->addToWishListUrl);  
		$this->removeWishListUrl = str_replace("{theme}", $this->theme, $this->removeWishListUrl);
		$this->lendingOptionsUrl = str_replace("{theme}", $this->theme, $this->lendingOptionsUrl);
		$this->itemDetailsUrl = str_replace("{theme}", $this->theme, $this->itemDetailsUrl);
		
		$this->logger = new Logger();
	}
	
	public function getSession($username = NULL)//To make compatible with OverDriveCacheSS. Username not use in this method
	{
		$this->logger->log("OverDriveSS getSession", PEAR_LOG_INFO);
		
		$url = $this->baseUrl.$this->theme."Default.htm";
		$this->init($url);
		
		$result = $this->exec();
		
		$urlWithSession = $this->odDOM->getUrlHTML302($result);
		return $this->odUtils->getSessionString($urlWithSession);
	}
	
	public function login($session, $username)
	{
		$this->logger->log("OverDriveSS login ".$username, PEAR_LOG_INFO);
		
		$url = $this->baseSecureUrl.$this->setSessionInURL($session, $this->loginUrl);
		$data = array('URL' => 'Default.htm', 
					  'LibraryCardILS' => $this->libraryCardILS,
					  'LibraryCardNumber' => $username);
		
		$this->init($url);
		$this->setPostData($data);
		$this->setNoCheckSSL();
		
		$result = $this->exec();
		
		$urlAfterLoginAttemp = $this->odDOM->getUrlHTML302($result);
		if($urlAfterLoginAttemp === "/".$this->baseServerName."/".$session."/10/50/en/Default.htm")
		{
			return true;
		}
		return false;
	}
	
	public function checkOut($session, $itemId, $formatId, $download = true)
	{
		$this->logger->log("OverDriveSS checkOut ".$itemId, PEAR_LOG_INFO);
		
		$url = $this->baseUrl."/".$session.$this->theme."BANGPurchase.dll?Action=OneClickCheckout&ForceLoginFlag=0&ReserveID=".$itemId."&URL=MyAccount.htm%3FPerPage=80";
		$this->init($url);

		$result = $this->exec();
		$urlAfterCheckOut = $this->odDOM->getUrlHTML302($result);
		if(preg_match("/^\/".$session."\/10\/50\/en\/Download\.htm\?TransactionID=/", $urlAfterCheckOut) && $download)
		{
			/*I DO NOT KNOW HOW TO TEST THIS AND THEN RETURN THE ITEM*/
			$downloadUrl = $this->setSessionInURL($session, $this->downloadUrl);
			$downloadUrl = str_replace("{itemID}", $itemId, $downloadUrl);
			$downloadUrl = str_replace("{formatId}", $formatId, $downloadUrl);
			
			$downloadUrl = $this->baseSecureUrl.$downloadUrl;
			$this->init($downloadUrl);
			$this->setNoCheckSSL();
			$result = $this->exec();
			
			$urlAfterDownload = $this->odDOM->getUrlHTML302($result);
			if(preg_match("/^http:\/\/ofs.contentreserve.com\/bin\/OFSGatewayModule.dll\/DogIsaDog\.azw\?RetailerID\=douglascounty/", $urlAfterDownload))
			{
				return true;
			}
			return true;
		}

		if(preg_match("/^\/".$session."\/10\/50\/en\/Download\.htm\?TransactionID=/", $urlAfterCheckOut) && !$download)
		{
			return true;
		}

		if(preg_match("/ErrorType\=220/", $urlAfterCheckOut))
		{
			$this->setError(220, "There are currently no copies of the selected title available for check out.");
		}
		
		if(preg_match("/\/".$session."\/10\/50\/en\/Error\.htm\?ErrorType\=710/", $urlAfterCheckOut))
		{
			$this->setError(710, "There have been too many titles checked out and returned by your account within a short period of time.");
		}
		
		if(preg_match("/\/".$session."\/10\/50\/en\/Error\.htm\?ErrorType\=730/", $urlAfterCheckOut))
		{
			$this->setError(730, "You have already checked out this title. To access it, return to your Bookshelf from the Account page.");
		}
		return false;
	}
	
	/*
	 * Not under TEST... :(
	 */
	public function chooseFormat($session, $itemId, $formatId)
	{
		$downloadUrl = $this->setSessionInURL($session, $this->downloadUrl);
		$downloadUrl = str_replace("{itemID}", $itemId, $downloadUrl);
		$downloadUrl = str_replace("{formatId}", $formatId, $downloadUrl);
			
		$downloadUrl = $this->baseSecureUrl.$downloadUrl;
		$this->init($downloadUrl);
		$this->setNoCheckSSL();
		$result = $this->exec();
			
		$urlAfterDownload = $this->odDOM->getUrlHTML302($result);
		if(preg_match("/^http:\/\/ofs.contentreserve.com\/bin\/OFSGatewayModule.dll\/DogIsaDog\.azw\?RetailerID\=douglascounty/", $urlAfterDownload))
		{
			return true;
		}
		return true;
	}
	
	public function returnTitle($session, $itemId)
	{
		$this->logger->log("OverDriveSS returnTitle ".$itemId, PEAR_LOG_INFO);
		
		$myAccountUrl = $this->baseSecureUrl.$this->setSessionInURL($session, $this->myAccountUrl);
		
		$this->init($myAccountUrl);
		$this->setNoCheckSSL();
		$result = $this->exec();
		$transactionId = $this->odDOM->getTransactionIdByItemID($result, $itemId);

		
		$url = $this->composeEarlyReturnUrl($session, $itemId, $transactionId);
		$this->init($url);
		$this->setNoCheckSSL();
		$result = $this->exec();
		
		$urlAfterEarlyReturn = $this->odDOM->getUrlHTML302($result);
		if(preg_match("/\/".$this->baseServerName."\/".$session."\/10\/50\/en\/MyAccount\.htm/", $urlAfterEarlyReturn))
		{
			return true;
		}
	
		if(preg_match("/\/".$this->baseServerName."\/".$session."\/10\/50\/en\/Error\.htm\?ErrorType\=2800/", $urlAfterEarlyReturn))
		{
			$this->setError(2800, "Cannot Return a Title that have been downloaded");
		}
		return false;
	}
	
	public function placeHold($session, $itemId, $formatId, $email)
	{
		$this->logger->log("OverDriveSS placeHold ".$itemId, PEAR_LOG_INFO);
		
		$url = $this->setSessionInURL($session, $this->placeHoldUrl);
		$placeHoldUrl = $this->baseSecureUrl.$url;
		$data = array('URL'    => 'WaitingListConfirm.htm',
					  'Format' => $formatId,
				      'ID'     => $itemId,
					  'Email'  => $email,
					  'Email2'  => $email,	);
		
		$this->init($placeHoldUrl);
		$this->setPostData($data);
		$this->setNoCheckSSL();
		$result = $this->exec();
		$urlAfterPlaceHold = $this->odDOM->getUrlHTML302($result);
		if(preg_match("/\/".$this->baseServerName."\/".$session."\/10\/50\/en\/WaitingListConfirm\.htm/", $urlAfterPlaceHold))
		{
			return true;
		}
		return false;
	}
	
	public function cancelHold($session, $itemId, $formatId)
	{
		$this->logger->log("OverDriveSS cancelHold ".$itemId, PEAR_LOG_INFO);
		
		$url = $this->setSessionInURL($session, $this->cancelHoldUrl);
		$url = str_replace("{itemID}", strtolower($itemId), $url);
		$url = str_replace("{formatId}", $formatId, $url);
		$cancelUrl = $this->baseSecureUrl.$url;
		
		$this->init($cancelUrl);
		$this->setNoCheckSSL();
		$result = $this->exec();
		$urlAfterCancelHold = $this->odDOM->getUrlHTML302($result);
		if(preg_match("/\/".$this->baseServerName."\/".$session."\/10\/50\/en\/waitinglistremove\.htm/", $urlAfterCancelHold))
		{
			return true;
		}
		return false;
	}
	
	public function addToWishList($session, $itemId)
	{
		$this->logger->log("OverDriveSS addToWishList ".$itemId, PEAR_LOG_INFO);
		
		return $this->WishListActions("add", $session, $itemId);
	}
	
	public function removeWishList($session, $itemId)
	{
		$this->logger->log("OverDriveSS removeWishList ".$itemId, PEAR_LOG_INFO);
		
		return $this->WishListActions("remove", $session, $itemId);
	}
	
	public function changeLendingOptions($session, $ebookDays, $audioBookDays, $videoDays, $disneyDays)
	{
		$this->logger->log("OverDriveSS changeLendingOptions", PEAR_LOG_INFO);
		
		$data = array('URL'    => 'MyAccount.htm?PerPage=80#myAccount4',
					  'class_1_preflendingperiod' => $ebookDays,
					  'class_2_preflendingperiod' => $audioBookDays,
					  'class_4_preflendingperiod' => $videoDays,
					  'class_5_preflendingperiod' => $disneyDays);
		
		$url = $this->setSessionInURL($session, $this->lendingOptionsUrl);
		$lendingUrl = $this->baseSecureUrl.$url;
		
		$this->init($lendingUrl);
		$this->setPostData($data);
		$this->setNoCheckSSL();
		$result = $this->exec();
		$urlAfterLending =  $this->odDOM->getUrlHTML302($result);
		if(preg_match("/\/".$this->baseServerName."\/".$session."\/10\/50\/en\/MyAccount\.htm\?PerPage\=80\#myAccount/", $urlAfterLending))
		{
			return true;
		}
		return false;
	}
	
	public function getLendingOptions($session)
	{
		$this->logger->log("OverDriveSS getLendingOptions", PEAR_LOG_INFO);
		
		$url = $this->setSessionInURL($session, $this->myAccountUrl);
		$myAccountUrl = $this->baseSecureUrl.$url;
		
		$this->init($myAccountUrl);
		$this->setNoCheckSSL();
		$result = $this->exec();
		
		return $this->odDOM->getLendingOptionsValue($result);
	}
	
	public function getItemDetails($session, $itemId)
	{
		$this->logger->log("OverDriveSS getItemDetails ".$itemId, PEAR_LOG_INFO);
		
		$url = $this->setSessionInURL($session, $this->itemDetailsUrl);
		$url = str_replace("{itemID}", $itemId, $url);
		$itemDetailUrl = $this->baseUrl.$url;
		
		$this->init($itemDetailUrl);
		$result = $this->exec();
		
		return $this->odDOM->getItemDetails($result, $itemId);
	}
	
	public function getMultipleItemsDetails($session, $itemsId)
	{
		$this->logger->log("OverDriveSS getMultipleItemsDetails ".implode(",",$itemsId), PEAR_LOG_INFO);
		
		$mh = curl_multi_init();
		
		$results = array();
		$chList = array();
		foreach ($itemsId as $itemId)
		{
			$url = $this->setSessionInURL($session, $this->itemDetailsUrl);
			$url = str_replace("{itemID}", $itemId, $url);
			$itemDetailUrl = $this->baseUrl.$url;
			
			$chList[$itemId] = curl_init($itemDetailUrl);
			curl_setopt($chList[$itemId], CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($chList[$itemId], CURLOPT_USERAGENT, $this->userAgent);
			curl_setopt($chList[$itemId], CURLOPT_HEADER, 0);
			
			curl_multi_add_handle($mh, $chList[$itemId]);
		}
		
		do {
			$mrc = curl_multi_exec($mh, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		
		while ($active && $mrc == CURLM_OK) {
			if (curl_multi_select($mh) != -1) {
				do {
					$mrc = curl_multi_exec($mh, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
		
		//getResults and close the handles

		foreach ($itemsId as $itemId)
		{
			$resultItemId = curl_multi_getcontent($chList[$itemId]);
			$results[$itemId] = $this->odDOM->getItemDetails($resultItemId, $itemId);
			
			curl_multi_remove_handle($mh, $chList[$itemId]);
		}
		curl_multi_close($mh);
		return $results;
	}
	
	
	public function getPatronCirculation($session)
	{
		$this->logger->log("OverDriveSS getPatronCirculation", PEAR_LOG_INFO);
		
		$myAccountUrl = $this->baseSecureUrl.$this->setSessionInURL($session, $this->myAccountUrl);
		$this->init($myAccountUrl);
		$this->setNoCheckSSL();
		$result = $this->exec();
		
		return $this->odDOM->getPatronCirculation($result, $this->baseSecureUrl."/".$session.$this->theme);
	}
	
	//Auxiliar methods
	public function getErrorCode()
	{
		return $this->errorCode;
	}
	
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
		
	//PRIVATES
	private function WishListActions($action, $session, $itemId)
	{
		if($action == 'add')
		{
			$url = $this->setSessionInURL($session, $this->addToWishListUrl);
		}
		else
		{
			$url = $this->setSessionInURL($session, $this->removeWishListUrl);
		}
	
		$url = str_replace("{itemID}", strtolower($itemId), $url);
		$removeWishUrl = $this->baseUrl.$url;
	
		$this->init($removeWishUrl);
		$result = $this->exec();
		$urlAfterAddWishList = $this->odDOM->getUrlHTML302($result);
		if(preg_match("/\/".$session."\/10\/50\/en\/WishList\.htm/", $urlAfterAddWishList))
		{
			return true;
		}
		return false;
	}
	
	
	private function composeEarlyReturnUrl($session, $itemId, $transactionId)
	{
		$url = $this->setSessionInURL($session, $this->earlyReturnUrl);
		$url = str_replace("{transID}", $transactionId, $url);
		$url = str_replace("{itemID}", strtolower($itemId), $url);
		return $this->baseSecureUrl.$url;
	}
	
	private function setSessionInURL($session, $url)
	{
		return str_replace("{SESSIONID}", $session, $url);
	}
	
	private function setError($errorCode, $errorMsg)
	{
		$this->errorCode = $errorCode;
		$this->errorMessage = $errorMsg;
	}
	
	private function setFollowLocation()
	{
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, TRUE);
	}
	
	private function setPostData($data)
	{
		
		curl_setopt($this->ch, CURLOPT_POST, TRUE);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
	}
	
	private function init($url)
	{
		$this->ch = curl_init($url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($this->ch, CURLOPT_HEADER, 0);
	}
	
	private function setNoCheckSSL()
	{
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	}
	
	private function exec()
	{
		//curl_setopt($this->ch, CURLOPT_PROXY, "127.0.0.1:8888");
		$result = curl_exec($this->ch);
		if($result === false)
		{
			echo curl_error($this->ch);
		}
		
		curl_close($this->ch);
		return $result;
	}
}
?>