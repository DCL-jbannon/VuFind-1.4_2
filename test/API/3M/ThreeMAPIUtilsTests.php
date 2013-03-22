<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/3M/ThreeMApiUtils.php';


class ThreeMAPIUtilsTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	const timestamp = "1351876249";
	const authStringGet = "EWe1OTbQeVjEI+IweOxsBYq8+K2dxoW1A2ssYkEtSEE=";
	const authStringPost = "s31i1vVN/GGQ2EmhH5xAD7zTIQH0ZznSLhzgAgQi3bs=";
	const authStringPut = "rJmr8hOLAPnvR3UuiDxVqtknx4gtpGcd22CnZ58GVjA=";
	const authStringDelete = "FWuro3S7zmJDhAE4zVsCpUEL4l51vg3dFi6fbzfAFx8=";
	const datetime = "Fri, 02 Nov 2012 17:10:49 GMT";	
	
	/**
	* method getAuthorization 
	* when called
	* should returnExpectedStringAs3MExample
	*/
	public function test_getAuthorization_called_returnExpectedStringAs3MExample()
	{
		$expected = "mTFTtgDEu4Ewg4TuQVlNt2Pdej2tVi+3kNzytLRhKwM=";
		$requestType = ThreeMAPIUtils::putRequest;
		$uriPath = "/checkout";
		$realSecretKey = "zJyecxf45LQjJelZ";
		$timestamp = gmmktime(17, 01, 57, 12, 6, 2012);
		$actual = ThreeMAPIUtils::getAuthorization($realSecretKey, $requestType, $uriPath, $timestamp);
		$this->assertEquals($expected, $actual);
	}
	
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
	
		$actual = ThreeMAPIUtils::getAuthorization($secretKey, $requestType, $uriPath, self::timestamp);
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
	
	/**
	* method getUTCDaysDiference 
	* when called
	* should returnCorrectly
	*/
	public function test_getDaysDiference_called_returnCorrectly()
	{
		$expected = "6";
		$dateStart = "2012-05-23T13:23:34";
		$dateEnd = "2012-05-29T14:25:39";
		$actual = ThreeMAPIUtils::getUTCDaysDiference($dateStart, $dateEnd);
		$this->assertEquals($expected, $actual);
	}
		
}
?>