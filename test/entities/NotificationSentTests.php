<?php

require_once dirname(__FILE__).'/../../vufind/web/sys/Notification/NotificationSent.php';

class NotificationSentTests extends PHPUnit_Framework_TestCase
{

	private $service;

	public function setUp()
	{
		$this->service = new NotificationSent();
		parent::setUp();
	}
	
	/**
	* method setType 
	* when notValid
	* should throw
	* @expectedException InvalidArgumentException
	*/
	public function test_setType_notValid_throw()
	{
		$this->service->setType("aDummyNonValidValue");
	}

}
?>