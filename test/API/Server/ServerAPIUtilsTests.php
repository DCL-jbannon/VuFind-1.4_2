<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Server/ServerAPIUtils.php';

class ServerAPIUtilsTests extends PHPUnit_Framework_TestCase
{
	const secretKey = "aDummySecretKey";
	private $service;
	private $clientSessionsMock;
		
	public function setUp()
	{
		$this->clientSessionsMock = $this->getMock("IAPISessionsDAO", array("getSessionByAccessTokenByClientId"));
		$this->service = new ServerAPIUtils();
		parent::setUp();		
	}

	/**
	* method generateAccessToken 
	* when called
	* should executesCorrectly
	* Thanks to: http://stackoverflow.com/questions/8571501/how-to-check-whether-the-string-is-base64-encoded-or-not
	*/
	public function test_generateAccessToken_called_executesCorrectly()
	{
		$actual = $this->service->generateAccessToken("aDummySecret");
		$result = preg_match("/^([A-Za-z0-9+\/]{4})*([A-Za-z0-9+\/]{4}|[A-Za-z0-9+\/]{3}=|[A-Za-z0-9+\/]{2}==)$/", $actual);
		$this->assertEquals(1, $result);
	}
	
	/**
	* method isValidAuthString 
	* when ItIsNotValid
	* should returnFalse
	*/
	public function test_isValidAuthString_ItIsNotValid_returnFalse()
	{
		$actual = $this->service->isValidAuthString("aNonValidAuthString", "aDummyClientAuthCode", "aDummySecret");
		$this->assertFalse($actual);
	}
	
	/**
	 * method isValidAuthString
	 * when itIsValid
	 * should returnTrue
	 */
	public function test_isValidAuthString_itIsValid_returnFalse()
	{
		$authStringValidAuthCode = "c8VNvBFh3QUudy90Qh/LXkrwnZMLLwvb4ZcPtyZXzSk=";
		$actual = $this->service->isValidAuthString($authStringValidAuthCode, "aDummyClientAuthCode", "aDummySecret");
		$this->assertTrue($actual);
	}

	/**
	 * method getAuthHeader
	 * when noAuthHeaderExists
	 * should throw
	 * @expectedException AuthorizationHeaderNotFoundException
	 */
	public function test_getAuthHeader_noAuthHeaderExists_throw()
	{
		$headers['Accept']="text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$this->service->getAuthHeader($headers);
	}
	
	/**
	* method getAuthHeader 
	* when called
	* should returnAuthHeader
	*/
	public function test_getAuthHeader_called_returnAuthHeader()
	{
		$expected = "aDummyAuthHeader";
		$headers['Accept']="text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$headers[ServerAPIConstants::AuthHeaderKey] = $expected;
		$actual = $this->service->getAuthHeader($headers);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getAccessTokenHeader
	 * when noAccessTokenHeader
	 * should throw
	 * @expectedException AccessTokenHeaderNotFoundException
	 */
	public function test_getAccessTokenHeader_noAccessTokenHeader_throw()
	{
		$headers['Accept']="text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$this->service->getAccessTokenHeader($headers);
	}
	
	/**
	 * method getAccessTokenHeader
	 * when called
	 * should returnAccessTokenHeader
	 */
	public function test_getAccessTokenHeader_called_returnAccessTokenHeader()
	{
		$expected = "aDummyAuthHeader";
		$headers['Accept']="text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$headers[ServerAPIConstants::AccessTokenHeaderKey] = $expected;
		$actual = $this->service->getAccessTokenHeader($headers);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMethodHeader
	 * when noAccessTokenHeader
	 * should throw
	 * @expectedException MethodHeaderNotFoundException
	 */
	public function test_getMethodHeader_noMethodHeader_throw()
	{
		$headers['Accept']="text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$this->service->getMethodHeader($headers);
	}
	
	/**
	 * method getMethodHeader
	 * when called
	 * should returnAccessTokenHeader
	 */
	public function test_getMethodHeader_called_returnAccessTokenHeader()
	{
		$expected = "aDummyMethodHeader";
		$headers['Accept']="text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
		$headers[ServerAPIConstants::MethodHeaderKey] = $expected;
		$actual = $this->service->getMethodHeader($headers);
		$this->assertEquals($expected, $actual);
	}	
}
?>