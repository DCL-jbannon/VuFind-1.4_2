<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/NotificationMailStatsDAO.php';
require_once dirname(__FILE__).'/../../vufind/classes/notifications/NotificationsConstants.php';
require_once dirname(__FILE__).'/../../vufind/web/sys/Notification/NotificationSent.php';

class NotificationMailStatsDAOTests extends DAOTests
{
	
	private $startDate;
	private $endDate;
	
	public function setUp()
	{
		parent::setUp();
		$this->startDate = date("Y-m-d")." 00:00:00";
		$this->endDate = date("Y-m-d")." 23:59:59";
	}
	
	
	/**
	 * method getReturnEcontentTyopesNumbersByRangeDate
	 * when noData
	 * should executesCorrectly
	 */
	public function test_getReturnEcontentTyopesNumbersByRangeDate_noData_executesCorrectly()
	{	
		$expected[NotificationsTypeMailStatistics::click] = array("count"=>0);
		$expected[NotificationsTypeMailStatistics::open] = array("count"=>0);
		$expected[NotificationsTypeMailStatistics::review] = array("count"=>0);
		$expected[NotificationsTypeMailStatistics::rate] = array("count"=>0);
		
		$actual = $this->service->getReturnEcontentTyopesNumbersByRangeDate($this->startDate, $this->endDate);
		$this->assertEquals($expected, $actual);
	}
		
	/**
	* method getReturnEcontentTyopesNumbersByRangeDate 
	* when called
	* should executesCorrectly
	*/
	public function test_getReturnEcontentTyopesNumbersByRangeDate_called_executesCorrectly()
	{
		$expected[NotificationsTypeMailStatistics::click] = array("count"=>2);
		$expected[NotificationsTypeMailStatistics::open] = array("count"=>1);
		$expected[NotificationsTypeMailStatistics::review] = array("count"=>1);
		$expected[NotificationsTypeMailStatistics::rate] = array("count"=>1);
		
		$nms = new $this->entityClassName();
		$nms->setUniqueIdentifier("aDummyUID");
		$nms->setType(NotificationsTypeMailStatistics::click);
		$nms->insert();
		$nms->insert();
		
		$nms->setType(NotificationsTypeMailStatistics::open);
		$nms->insert();
		
		$nms->setType(NotificationsTypeMailStatistics::review);
		$nms->insert();
		
		$nms->setType(NotificationsTypeMailStatistics::rate);
		$nms->insert();
		
		$nms = new NotificationSent();
		$nms->setUniqueIdentifier("aDummyUID");
		$nms->setType(NotificationsType::returnEcontent);
		$nms->insert();
		
		$actual = $this->service->getReturnEcontentTyopesNumbersByRangeDate($this->startDate, $this->endDate);
		
		$this->assertEquals($expected, $actual);
	}
	
	public function getObjectToInsert()
	{
		$nms = new $this->entityClassName();
		$nms->setUniqueIdentifier("aDummyUID");
		$nms->setType(NotificationsTypeMailStatistics::click);
		return $nms;
	}
	
	public function getTablesToTruncate()
	{
		return array("notifications_mail_statistics", "notifications_sent");
	}
	
	public function getNameDAOClass()
	{
		return 'NotificationMailStatsDAO';
	}
	
	public function getEntityClassName()
	{
		return 'NotificationMailStats';
	}

}
?>