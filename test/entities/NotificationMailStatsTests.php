<?php

require_once dirname(__FILE__).'/../../vufind/web/sys/Notification/NotificationMailStats.php';

class NotificationMailStatsTests extends PHPUnit_Framework_TestCase
{

	private $service;

	public function setUp()
	{
		$this->service = new NotificationMailStats();
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
	
	/**
	* method setType 
	* when validType
	* should executeCorrectly
	* @dataProvider DP_setType_validType
	*/
	public function test_setType_validType_executeCorrectly($type)
	{
		$this->service->setType($type);
	}

	public function DP_setType_validType()
	{
		return array(
					array(NotificationsTypeMailStatistics::click),
					array(NotificationsTypeMailStatistics::open),
					array(NotificationsTypeMailStatistics::rate),
					array(NotificationsTypeMailStatistics::review),
			   );
	}
		

}
?>