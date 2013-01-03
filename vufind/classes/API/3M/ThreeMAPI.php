<?php
require_once dirname(__FILE__).'/ThreeMAPIWrapper.php';

interface IThreeMAPI{}

class ThreeMAPI implements IThreeMAPI
{
	private $threemAPIWrapper;
	
	public function __construct(IThreeMAPIWrapper $threeAPIWrapper = NULL)
	{
		if(!$threeAPIWrapper) $threeAPIWrapper = new ThreeMAPIWrapper();
		$this->threemAPIWrapper = $threeAPIWrapper;
	}
	
	public function getItemDetails($itemId)
	{
		return $this->callMethodReturnResultsByParameter("getItemDetails", $itemId);
	}
	
	public function getItemsDetails($itemsId)
	{
		return $this->callMethodReturnResultsByParameter("getItemsDetails", $itemsId);
	}
	
	public function checkout($itemId, $patronId)
	{
		return $this->callMethodReturnResults("checkout", $itemId, $patronId);
	}
	
	public function checkin($itemId, $patronId)
	{
		return $this->callMethodReturnHttpCode("checkin", $itemId, $patronId);
	}
	
	public function placeHold($itemId, $patronId)
	{
		return $this->callMethodReturnResults("placeHold", $itemId, $patronId);
	}
	
	public function cancelHold($itemId, $patronId)
	{
		return $this->callMethodReturnHttpCode("cancelHold", $itemId, $patronId);
	}
	
	public function getItemCirculation($itemId)
	{
		return $this->callMethodReturnResultsByParameter("getItemCirculation", $itemId);
	}
	
	public function getItemsCirculation($itemId)
	{
		return $this->callMethodReturnResultsByParameter("getItemsCirculation", $itemId);
	}
	
	public function getPatronCirculation($patronId)
	{
		return $this->callMethodReturnResultsByParameter("getPatronCirculation", $patronId);
	}
	
	//private Calls
	private function callMethodReturnResultsByParameter($methodName, $parameter)
	{
		$result = $this->threemAPIWrapper->$methodName($parameter);
		return $this->checkResultForErrorMessage($result);
	}
	
	private function checkResultForErrorMessage($result)
	{
		$name = (string)$result->getName();
		if($name == "Error")
		{
			return false;
		}
		return $result;
	}
	
	private function callMethodReturnResults($methodName, $itemId, $patronId)
	{
		$result = $this->threemAPIWrapper->$methodName($itemId, $patronId);
		return $this->checkResultForErrorMessage($result);
	}
	
	private function callMethodReturnHttpCode($methodName, $itemId, $patronId)
	{
		$result = $this->threemAPIWrapper->$methodName($itemId, $patronId);
		if($result != "200")
		{
			return false;
		}
		return true;
	}
	
}

?>