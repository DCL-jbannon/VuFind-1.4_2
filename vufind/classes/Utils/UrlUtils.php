<?php

interface IUrlUtils{}

class UrlUtils implements IUrlUtils{
	
	
	public function getParamsString($paramsArray)
	{
		if(empty($paramsArray))
		{
			return '';
		}
		
		if (!is_array($paramsArray))
		return $paramsArray;
		
		$paramsString = '';
		foreach($paramsArray as $key=>$val)
		{
			if(!empty($paramsString))
			{
				$paramsString .= '&';
			}
			$paramsString .= $key.'='.$val;
		}
		return $paramsString;
	}
	
	public function encodeParams($params)
	{
		return urlencode($params);
	}
	
}

?>