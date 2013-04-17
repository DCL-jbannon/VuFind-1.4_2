<?php
interface IThreeMAPIWrapper{}
require_once dirname(__FILE__).'/ThreeMApiUtils.php';

class ThreeMAPIWrapper implements IThreeMAPIWrapper
{
	const apiVersion = "1.0";
	const baseUriPath = "/cirrus/library/";
	
	const uriPathGetItemDetails = "/item";
	const uriPathGetItemsDetails = "/items";
	const uriPathCheckOutItem = "/checkout";
	const uriPathCheckInItem = "/checkin";
	const uriPathPlaceHoldItem = "/placehold";
	const uriPathCancelHoldItem = "/cancelhold";
	const uriPathCirculationItem = "/circulation/item";
	const uriPathCirculationItems = "/circulation/items";
	const uriPathPatronCirculation = "/circulation/patron";
	
	private $libraryId;
	private $baseLibraryUrl;
	private $accesKey;
	private $ch;
	
	//For CheckOut Method
	private $putBodyRead = false;
	
	public function __construct()
	{
		global $configArray;
		$this->libraryId = $configArray['3MAPI']['libraryId'];
		$this->baseLibraryUrl = $configArray['3MAPI']['url'].self::baseUriPath.$this->libraryId;
		$this->accesKey = $configArray['3MAPI']['accesKey'];
	}
	
	
	public function getItemDetails($itemId)
	{
		$uriPath = self::uriPathGetItemDetails."/".$itemId;
		return $this->executeGetCall($uriPath);
	}
	
	/**
	 * itemsId format:  id1,id2,id3,...,idN
	 * @param string $itemsId
	 */
	public function getItemsDetails($itemsId)
	{
		$uriPath = self::uriPathGetItemsDetails."/".$itemsId;
		return $this->executeGetCall($uriPath);
	}
	
	public function checkout($itemId, $patronId)
	{
		$requestBody  = "<CheckoutRequest>";
		$requestBody .= "<ItemId>".$itemId."</ItemId>";
		$requestBody .= "<PatronId>".$patronId."</PatronId>";
		$requestBody .= "</CheckoutRequest>";
		return $this->executePutCall(self::uriPathCheckOutItem, $requestBody);
	}
	
	public function placeHold($itemId, $patronId)
	{
		$requestBody  = "<PlaceHoldRequest>";
		$requestBody .= "<ItemId>".$itemId."</ItemId>";
		$requestBody .= "<PatronId>".$patronId."</PatronId>";
		$requestBody .= "</PlaceHoldRequest>";
		return $this->executePutCall(self::uriPathPlaceHoldItem, $requestBody);
	}
	
	public function cancelHold($itemId, $patronId)
	{
		$requestBody  = "<CancelHoldRequest>";
		$requestBody .= "<ItemId>".$itemId."</ItemId>";
		$requestBody .= "<PatronId>".$patronId."</PatronId>";
		$requestBody .= "</CancelHoldRequest>";
		return $this->executeCallReturnHttpCode($requestBody, self::uriPathCancelHoldItem);
	}
	
	public function checkin($itemId, $patronId)
	{
		$requestBody  = "<CheckinRequest>";
		$requestBody .= "<ItemId>".$itemId."</ItemId>";
		$requestBody .= "<PatronId>".$patronId."</PatronId>";
		$requestBody .= "</CheckinRequest>";
		return $this->executeCallReturnHttpCode($requestBody, self::uriPathCheckInItem);
	}
	
	public function getItemCirculation($itemId)
	{
		$uriPath = self::uriPathCirculationItem."/".$itemId;
		return $this->executeGetCall($uriPath);
	}
	
	/**
	 * itemsId format:  id1,id2,id3,...,idN
	 * @param string $itemsId
	 */
	public function getItemsCirculation($itemsId)
	{
		$uriPath = self::uriPathCirculationItems."/".$itemsId;
		return $this->executeGetCall($uriPath);
	}
	
	public function getPatronCirculation($patronId)
	{
		$uriPath = self::uriPathPatronCirculation."/".$patronId;
		return $this->executeGetCall($uriPath);
	}
	
	/** 
	 * 
	 * Utils & Commons fmethods 
	 * 
	 **/
	
	private function executeGetCall($uriPath)
	{
		$threeMheaders = $this->getThreeMHeaders($uriPath, ThreeMAPIUtils::getRequest);
		$this->initCurl();
		$this->setCommonOptions($threeMheaders, $uriPath);
		return $this->exec();
	}
	
	private function executeCallReturnHttpCode($data, $uriPath)
	{
		$threeMheaders = $this->getThreeMHeaders($uriPath, ThreeMAPIUtils::postRequest);
		
		$this->initCurl();
		$this->setCommonOptions($threeMheaders, $uriPath);
		
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
		return $this->execReturnHttpCode();
	}
	
	private function executePutCall($uriPath, $bodyData)
	{
		$this->checkPutBody = $bodyData;
		$threeMheaders = $this->getThreeMHeaders($uriPath, ThreeMAPIUtils::putRequest);
	
		$this->initCurl();
		$this->setCommonOptions($threeMheaders, $uriPath);
	
		$fd = fopen(__FILE__, "r");
	
		curl_setopt($this->ch, CURLOPT_PUT, 1);
		curl_setopt($this->ch, CURLOPT_INFILE, $fd);
		curl_setopt($this->ch, CURLOPT_INFILESIZE, strlen($this->checkPutBody));
		curl_setopt($this->ch, CURLOPT_READFUNCTION, array($this,'putBody'));
	
		$result = $this->exec();
		fclose($fd);
		return $result;
	}
	
	private function getThreeMHeaders($uriPath, $requestType)
	{
		return ThreeMAPIUtils::getHeadersArray($this->accesKey, $requestType, $this->getBaseUriPath().$uriPath, self::apiVersion);
	}
	
	
	private function putBody()
	{
		if($this->putBodyRead)
		{
			return ''; //END OF FILE Not necessary. Just in case.
		}
		$this->putBodyRead = true;
		return $this->checkPutBody;
	}
	
	private function initCurl()
	{
		$this->ch = curl_init();
	}
	
	private function setCommonOptions($threeMheaders, $uriPath)
	{
		$url = $this->baseLibraryUrl.$uriPath;
		//var_dump($url);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $threeMheaders);
		
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HEADER, false);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->ch, CURLOPT_URL, $url);
		
	}
	
	/**
	 * http://us3.php.net/manual/en/class.simplexmlelement.php
	 * return SimpleXMLElement
	 */
	private function exec()
	{
		$result = curl_exec($this->ch);	
		//var_dump($result);die();
		$this->closeChannel();
		return @simplexml_load_string($result);
	}
	
	private function execReturnHttpCode()
	{
		$resultBody = curl_exec($this->ch);
		$result = curl_getinfo($this->ch,CURLINFO_HTTP_CODE);
		//var_dump($resultBody);die();
		$this->closeChannel();
		return $result;
	}
	
	private function closeChannel()
	{
		curl_close($this->ch);
	}	
	
	
	public function getBaseUriPath()
	{
		return self::baseUriPath.$this->libraryId;
	}
}
?>