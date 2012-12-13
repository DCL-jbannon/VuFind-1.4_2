<?php
require_once dirname(__FILE__).'/OverDriveAPIWrapper.php';
require_once dirname(__FILE__).'/OverDriveAPIException.php';
require_once dirname(__FILE__).'/CollectionOverDriveIterator.php';

interface IOverDriveAPI{}

class OverDriveAPI implements IOverDriveAPI{
	
	private $odw;
	private $clientKey;
	private $clientSecret;
	private $libraryId;
	
	private $accessToken;
	private $productsUrl;
	private $totalItems;
	
	public function __construct($clientKey, $clientSecret, $libraryId, IOverDriveAPIWrapper $overDriveWrapper = NULL)
	{
		if(!$overDriveWrapper) $overDriveWrapper = new OverDriveAPIWrapper();
		$this->odw = $overDriveWrapper;
		
		$this->clientKey = $clientKey;
		$this->clientSecret = $clientSecret;
		$this->libraryId = $libraryId;
	}
	
	public function login()
	{
		$result = $this->odw->login($this->clientKey, $this->clientSecret);
		$this->accessToken = $result->access_token;
		return $result;
	}
	
	public function getInfoDCLLibrary()
	{
		$result = $this->odw->getInfoDCLLibrary($this->accessToken, $this->libraryId);
		$this->productsUrl = $result->links->products->href;
		return $result;
	}
	
	public function getDigitalCollection($limit, $offset)
	{
		return  $this->odw->getDigitalCollection($this->accessToken, $this->productsUrl, $limit, $offset);
	}
	
	public function getItemAvailability($itemId)
	{
		return  $this->odw->getItemAvailability($this->accessToken, $this->productsUrl, $itemId);
	}
	
	public function getItemMetadata($itemId)
	{
		return  $this->odw->getItemMetadata($this->accessToken, $this->productsUrl, $itemId);
	}
	
	
	/************/
	public function getAccessToken()
	{
		return $this->accessToken;
	}
	
	public function getProductsUrl()
	{
		return $this->productsUrl;
	}
	
	/** TEST PURPOUSE **/
	public function setAccessToken($accessToken)
	{
		$this->accessToken = $accessToken;
	}
	
	public function setProductsUrl($producstUrl)
	{
		$this->productsUrl = $producstUrl;
	}
	public function setTotalItems($total)
	{
		$this->totalItems = $total;
	}
	
}

?>