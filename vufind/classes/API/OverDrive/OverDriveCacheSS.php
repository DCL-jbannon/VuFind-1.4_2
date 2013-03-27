<?php
require_once dirname(__FILE__).'/OverDriveSS.php';
require_once dirname(__FILE__).'/../../memcache/MemcacheServices.php';
require_once dirname(__FILE__).'/../../../web/sys/Logger.php';

class OverDriveCacheSS implements IOverDriveSS
{
	const keyGetItemDetails = "OverDriveCacheSS_getItemDetails_";
	const keyGetPatronCirculation = "OverDriveCacheSS_getPatronCirculation_";
	const keyLogin = "OverDriveCacheSS_login_";
	const keySessionNoUsername = "OverDriveCacheSS_session_noUsername";
	const keySessionUsername = "OverDriveCacheSS_session_";
	
	private $overDriveSS;
	private $memcacheServices;
	private $logger;
	
	public function __construct($baseUrl, $theme, $libraryCardILS, $baseSecureUrl, 
			                    IOverDriveSS $overDriveSS = NULL,
			                    IMemcacheServices $memcacheServices = NULL)
	{
		if(!$overDriveSS) $overDriveSS = new OverDriveSS($baseUrl, $theme, $libraryCardILS, $baseSecureUrl);
		$this->overDriveSS = $overDriveSS;
		
		if(!$memcacheServices) $memcacheServices = new MemcacheServices();
		$this->memcacheServices = $memcacheServices;
		
		$this->logger = new Logger();
	}
	
	public function getItemDetails($session, $itemId)
	{
		$this->logger->log("OverDriveCacheSS getItemDetails ".$session." ".$itemId, PEAR_LOG_INFO);
		return $this->memcacheServices->call($this->overDriveSS, "getItemDetails", array($session, $itemId), self::keyGetItemDetails.$session.$itemId, 30);
	}
	
	public function getPatronCirculation($session)
	{
		$this->logger->log("OverDriveCacheSS getPatronCirculation".$session, PEAR_LOG_INFO);
		return $this->memcacheServices->call($this->overDriveSS, "getPatronCirculation", array($session), self::keyGetPatronCirculation.$session, 30);
	}
	
	public function getMultipleItemsDetails($session, $itemsId)
	{
		$this->logger->log("OverDriveCacheSS getMultipleItemsDetails ".$session." ".implode(" ", $itemsId), PEAR_LOG_INFO);
		
		$result = $this->overDriveSS->getMultipleItemsDetails($session, $itemsId);
		foreach ($result as $key=>$val)
		{
			$this->memcacheServices->set(self::keyGetItemDetails.$session.$key, $val, 30);
		}
		return $result;
	}

	public function getSession($username = NULL)
	{
		$this->logger->log("OverDriveCacheSS getSession ".$username, PEAR_LOG_INFO);	
		if($username === NULL)
		{
			return $this->memcacheServices->call($this->overDriveSS, "getSession", array($username), OverDriveCacheSS::keySessionNoUsername, 300);
		}
		else
		{
			return $this->memcacheServices->call($this->overDriveSS, "getSession", array($username), OverDriveCacheSS::keySessionUsername.$username, 300);
		}
	}
	
	public function login($session, $username)
	{
		$this->logger->log("OverDriveCacheSS login ".$session ." ".$username, PEAR_LOG_INFO);
		return $this->memcacheServices->call($this->overDriveSS, "login", array($session, $username), OverDriveCacheSS::keyLogin.$session.$username, 300);
	}
	
	public function checkOut($session, $itemId, $formatId, $download = true)
	{
		return $this->overDriveSS->checkOut($session, $itemId, $formatId);	
	}
	
	public function returnTitle($session, $itemId)
	{
		return $this->overDriveSS->returnTitle($session, $itemId);
	}
	
	public function placeHold($session, $itemId, $formatId, $email)
	{
		return $this->overDriveSS->placeHold($session, $itemId, $formatId, $email);	
	}
	
	public function cancelHold($session, $itemId, $formatId)
	{
		return $this->overDriveSS->cancelHold($session, $itemId, $formatId);
	}
	
	public function addToWishList($session, $itemId)
	{
		return $this->overDriveSS->addToWishList($session, $itemId);	
	}
	
	public function removeWishList($session, $itemId)
	{
		return $this->overDriveSS->removeWishList($session, $itemId); 	
	}
	
	public function changeLendingOptions($session, $ebookDays, $audioBookDays, $videoDays, $disneyDays)
	{
		return $this->overDriveSS->changeLendingOptions($session, $ebookDays, $audioBookDays, $videoDays, $disneyDays);
	}
	public function getLendingOptions($session)
	{
		return $this->overDriveSS->getLendingOptions($session);	
	}
	
	public function chooseFormat($session, $itemId, $formatId)
	{
		return $this->overDriveSS->chooseFormat($session, $itemId, $formatId);
	}

}
?>