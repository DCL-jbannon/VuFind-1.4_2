<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/3M/ThreeMAPIUtils.php';


class ThreeMAPIUtilsTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	const timestamp = "1351876249";
	const authStringGet = "MTE2N2I1MzkzNmQwNzk1OGM0MjNlMjMwNzhlYzZjMDU4YWJjZjhhZDlkYzY4NWI1MDM2YjJjNjI0MTJkNDg0MQ==";
	const authStringPost = "YjM3ZDYyZDZmNTRkZmM2MTkwZDg0OWExMWY5YzQwMGZiY2QzMjEwMWY0NjczOWQyMmUxY2UwMDIwNDIyZGRiYg==";
	const authStringPut = "YWM5OWFiZjIxMzhiMDBmOWVmNDc3NTJlODgzYzU1YWFkOTI3Yzc4ODJkYTQ2NzFkZGI2MGE3Njc5ZjA2NTYzMA==";
	const authStringDelete = "MTU2YmFiYTM3NGJiY2U2MjQzODQwMTM4Y2Q1YjAyYTU0MTBiZTI1ZTc1YmUwZGRkMTYyZTlmNmYzN2MwMTcxZg==";
	const datetime = "Fri, 02 Nov 2012 17:10:49 GMT";
	
	/**
	* method getDatetime
	* when called
	* should returnCorrectDate
	*/
	public function test_getDatetime_called_returnCorrectDate()
	{
		$expected = self::datetime;
		$actual = ThreeMAPIUtils::getDatetime(self::timestamp);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getAuthorization
	 * when URIdoesNotStartWithSlash
	 * should addSlashAtBegining
	 */
	public function test_getAuthorization_URIdoesNotStartWithSlash_addSlashAtBegining()
	{
		$expected = self::authStringGet;
		$requestType = ThreeMAPIUtils::getRequest;
		$uriPath = "a Dummy URI path";
		$secretKey = "a Dummy Secret Key";
	
		$actual = ThreeMAPIUtils::getAuthorization($secretKey, $requestType, $uriPath,self::timestamp);
		$this->assertEquals($expected, $actual, self::timestamp);
	}
	
	/**
	* method getAuthorization
	* when called
	* should returnCorrectValue
	*/
	public function test_getAuthorization_called_returnCorrectValue()
	{
		$expected = self::authStringGet;
		$requestType = ThreeMAPIUtils::getRequest;
		$uriPath = "/a Dummy URI path";
		$secretKey = "a Dummy Secret Key";
		$actual = ThreeMAPIUtils::getAuthorization($secretKey, $requestType, $uriPath,self::timestamp);
		$this->assertEquals($expected, $actual, self::timestamp);
	}
	
		
	/**
	* method getHeadersArray
	* when getDeleteRequest
	* should returnCorrectly
	* @dataProvider DP_getDeleteRequest
	*/
	public function test_getHeadersArray_getDeleteRequest_returnCorrectly($requestType, $authString)
	{
		$expected[0] = '3mcl-datetime: '.self::datetime;
		$expected[1] = '3mcl-Authorization: '.ThreeMAPIUtils::accountId.':'.$authString;
		$expected[2] = '3mcl-apiversion: 1.0';
		
		$apiVersion = "1.0";
		$uriPath = "/a Dummy URI path";
		$secretKey = "a Dummy Secret Key";
		$timeStamp = self::timestamp;
		
		$actual = ThreeMAPIUtils::getHeadersArray($secretKey, $requestType, $uriPath, $apiVersion, self::timestamp);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getDeleteRequest()
	{
		return array(
						array(ThreeMAPIUtils::getRequest, self::authStringGet),
						array(ThreeMAPIUtils::deleteRequest, self::authStringDelete),
					);
	}
	
	/**
	 * method getHeadersArray
	 * when postPutTypeRequest
	 * should returnCorrectly
	 * @dataProvider DP_postPutTypeRequest
	 */
	public function test_getHeadersArray_postPutTypeRequest_returnCorrectly($requestType, $authString)
	{
		$expected[0] = '3mcl-datetime: '.self::datetime;
		$expected[1] = '3mcl-Authorization: '.ThreeMAPIUtils::accountId.':'.$authString;
		$expected[2] = '3mcl-apiversion: 1.0';
		$expected[3] = 'Content-Type: text/xml; charset=utf-8';
	
		$apiVersion = "1.0";
		$uriPath = "/a Dummy URI path";
		$secretKey = "a Dummy Secret Key";
		$timeStamp = self::timestamp;
	
		$actual = ThreeMAPIUtils::getHeadersArray($secretKey, $requestType, $uriPath, $apiVersion, self::timestamp);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_postPutTypeRequest()
	{
		return array(
						array(ThreeMAPIUtils::postRequest, self::authStringPost),
						array(ThreeMAPIUtils::putRequest, self::authStringPut)
					);		
	}
	
	/**
	* method getBaseLibraryUrl
	* when called
	* should returnCorrectLibraryUrl
	*/
	public function test_getBaseLibraryUrl_called_returnCorrectLibraryUrl()
	{
		$libraryId = "aDummyLibrarId";
		$baseUrl = "http://aDummyBaseUrl";
		$expected = $baseUrl."/library/".$libraryId;
		$actual = ThreeMAPIUtils::getBaseLibraryUrl($baseUrl, $libraryId);
		$this->assertEquals($expected, $actual);
	}
	
}

?>