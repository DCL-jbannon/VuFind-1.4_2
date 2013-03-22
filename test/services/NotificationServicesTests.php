<?php
require_once dirname(__FILE__).'/../../vufind/classes/services/NotificationServices.php';

class NotificationServicesTests extends PHPUnit_Framework_TestCase
{
	private $service;
	private $notificationUtilsMock;
	private $notificationMailStatsMock;
	
	const module = "aDummyModule";
	const action = "aDummyAction";
	const notuid = "aDummyNotUid";
	
	public function setUp()
	{
		$this->notificationUtilsMock = $this->getMock("INotificationUtils", array("isValidNotificationUID"));
		$this->notificationMailStatsMock = $this->getMock("INotificationMailStats", array("setType","setUniqueIdentifier", "insert"));
		
		$this->service = new NotificationServices();
		parent::setUp();		
	}
	
	/**
	* method insertMailStats 
	* when uniqueIdentifierIsNotValid
	* should doNotInsertMailStats
	*/
	public function test_insertMailStats_uniqueIdentifierIsNotValid_doNotInsertMailStats()
	{
		$notuid = "aDummyNotUid";
		$_REQUEST['notuid'] = $notuid;
		
		$this->prepareNotificationUtilsMock(self::notuid, false);
		
		
		$this->notificationMailStatsMock->expects($this->never())
										->method("insert");
		
		$this->service->insertMailStats(self::notuid, self::module, self::action, NULL, $this->notificationUtilsMock, $this->notificationMailStatsMock);
	}
	
	/**
	 * method insertMailStats
	 * when uniqueIdentifierIsValid
	 * should doNotInsertMailStats
	 * @dataProvider DP_insertMailStats_uniqueIdentifierIsValid
	 */
	public function test_insertMailStats_uniqueIdentifierIsValid_doNotInsertMailStats($module, $action, $method, $typeMailStats)
	{
		$notuid = "aDummyNotUid";
		$_REQUEST['notuid'] = $notuid;
		
		$this->prepareNotificationUtilsMock(self::notuid, true);
		
		$this->notificationMailStatsMock->expects($this->once())
										->method("setType")
										->with($this->equalTo($typeMailStats));
		
		$this->notificationMailStatsMock->expects($this->once())
										->method("setUniqueIdentifier")
										->with($this->equalTo($notuid));
		
		$this->notificationMailStatsMock->expects($this->once())
										->method("insert");
	
		$this->service->insertMailStats(self::notuid, $module, $action, $method, $this->notificationUtilsMock, $this->notificationMailStatsMock);
	}
	
	public function DP_insertMailStats_uniqueIdentifierIsValid()
	{
		return array(
						array("aDummyModule", "aDummyAction", NULL, NotificationsTypeMailStatistics::click),
						array("Notification", "MailOpen", NULL, NotificationsTypeMailStatistics::open),
						array("", "", NULL, NotificationsTypeMailStatistics::click),
						array("EcontentRecord", "AJAX","SaveComment", NotificationsTypeMailStatistics::review),
						array("EcontentRecord", "AJAX","RateTitle", NotificationsTypeMailStatistics::rate),
					);
	}
	
	
	//prepares
	private function prepareNotificationUtilsMock($notuid, $returnFindValue)
	{
		$this->notificationUtilsMock->expects($this->once())
									->method("isValidNotificationUID")
									->with($this->equalTo($notuid))
									->will($this->returnValue($returnFindValue));
	}
	
}
?>