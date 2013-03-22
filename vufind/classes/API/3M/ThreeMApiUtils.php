<?php

class ThreeMAPIUtils {

	const getRequest    = "GET";
	const putRequest    = "PUT";
	const postRequest   = "POST";
	const deleteRequest = "DELETE";
	const accountId = "3MCLAUTH DouglasCounty.Production01";
	
	private static $signedString;
	private static $plainString;
	
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
		$stringHashed = hash_hmac("sha256", $auth, $key, TRUE);
		$signatureBase64Encoded = base64_encode($stringHashed);
		
		self::$signedString = $signatureBase64Encoded;
		self::$plainString = $auth;
		
		return $signatureBase64Encoded;
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
		//$headers[4] = 'DEBUGDCL: Signed String '.self::$signedString;
		//$headers[5] = 'DEBUGDCL: Plain String '.self::$plainString;
		return $headers;
	}
	
	public static function getBaseLibraryUrl($baseUrl, $libraryId)
	{
		return $baseUrl."/library/".$libraryId;
	}
	
	
	/**
	 * Dates in UTC Format
	 * @param string $dateStart
	 * @param string $dateEnd
	 */
	public function getUTCDaysDiference($dateStart, $dateEnd)
	{
		$partsDS = explode("T", $dateStart);
		$partsDE = explode("T", $dateEnd);
		return (strtotime($partsDE[0]) - strtotime($partsDS[0]))/(24 * 60 * 60);
	}
}
?>