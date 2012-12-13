<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveHttpResponse.php';

class OverDriveHttpResponseTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new OverDriveHttpResponse();
		parent::setUp();		
	}
	
	/**
	* method checkResponseCode
	* when ResponseCodeIs200
	* should returnTrue
	*/
	public function test_checkResponseCode_ResponseCodeIs200_returnTrue()
	{
		$actual = $this->service->checkResponseCode(200);
		$this->assertTrue($actual);
	}	

	/**
	 * method checkResponseCode
	 * when ResponseCodeIs401
	 * should returnFalse
	 */
	public function test_checkResponseCode_ResponseCodeIs401_assertFalse()
	{
		$actual = $this->service->checkResponseCode(401);
		$this->assertFalse($actual);
	}
	
	/**
	* method checkResponseCode
	* when ResponseCodeIsOther
	* should throw
	* @expectedException OverDriveHttpResponseException
	* @dataProvider DP_checkResponseCode_ResponseCodeIsOther
	*/
	public function test_checkResponseCode_ResponseCodeIsOther_throw($code)
	{
		$actual = $this->service->checkResponseCode($code);
	}
	
	/**
	 * Response Code provide by OverDrive
	 */
	public function DP_checkResponseCode_ResponseCodeIsOther()
	{
		return array(
					array(400),
					array(404),
					array(500),
					array(503)
				);
	}
	
}



?>
