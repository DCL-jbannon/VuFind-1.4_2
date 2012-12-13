<?php

class ThreeMAPIUtils {

	const getRequest    = "GET";
	const putRequest    = "PUT";
	const postRequest   = "POST";
	const deleteRequest = "DELETE";
	const accountId = "3MCLAUTH DouglasCounty.Production01";
	
	public static function getDatetime($timestamp = NULL) {
		if(!$timestamp) $timestamp = mktime();
		return gmdate("D, d M Y G:i:s", $timestamp)." GMT";
	}

	public static function getAuthorization($key, $requestType, $uriPath, /*Test Purpouse*/ $timestamp = NULL)
	{
		if(!preg_match("/^\//", $uriPath))
		{
			$uriPath = "/".$uriPath;
		}
		
		$auth = self::getDatetime($timestamp)."\n".$requestType."\n".$uriPath;
		return base64_encode(hash_hmac("sha256", $auth, $key));
	}
	
	public static function getHeadersArray($key, $requestType, $uriPath, $apiVersion, /*Test Purpouse*/ $timestamp = NULL)
	{
		$headers = array();
		$headers[2] = '3mcl-apiversion: '.$apiVersion;
		$headers[1] = '3mcl-Authorization: '.self::accountId.':'.self::getAuthorization($key, $requestType, $uriPath, $timestamp);
		$headers[0] = '3mcl-datetime: '.self::getDatetime($timestamp);
		switch($requestType)
		{
			case self::postRequest:
			case self::putRequest:
				$headers[3] = 'Content-Type: text/xml; charset=utf-8';
				break;
			default://Do Nothing
				break;
		}
		return $headers;
	}
	
	public static function getBaseLibraryUrl($baseUrl, $libraryId)
	{
		return $baseUrl."/library/".$libraryId;
	}

}

?>