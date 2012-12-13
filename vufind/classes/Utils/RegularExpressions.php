<?php

interface IRegularExpressions{}

class RegularExpressions implements IRegularExpressions{

	public function addAnchorHrefLink($html)
	{
		$htmlNormalized = preg_replace('/href="(?!mailto)(?!http(?s))(?!:\/\/)(.*)"/i', 'href="#$1"', $html);

		return $htmlNormalized;

	}
	
	public function getFieldValueFromURL($url, $paramName)
	{
		$matches = array();
		preg_match("/".$paramName."=(.*)/",$url, $matches);
		if(!empty($matches))
		{
			$paramValue = explode("&",$matches[1]);
			return $paramValue[0];
		}
		return '';
	}

}
?>