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
	 * method getEcontentIdActions
	 * when noResults
	 * should returnCorrectly
	 */
	public function test_getEcontentIdActions_noResults_returnCorrectly()
	{
		$expected = BaseDAO::noResult();
		$actual = $this->service->getEcontentIdActions($this->startDate, $this->endDate);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getEcontentIdActions 
	* when called
	* should returnCorrectly
	*/
	public function test_getEcontentIdActions_called_returnCorrectly()
	{
		$econtentRecordId = 12;
		$econtentRecordId2 = 45;
		$expected[] = array($econtentRecordId,  "open");
		$expected[] = array($econtentRecordId,  "click");
		$expected[] = array($econtentRecordId2, "rate");
		
		$this->insetNotificationSent(NotificationsType::returnEcontent, "aDummyUID", NULL, "ID: ".$econtentRecordId);
		$this->insetNotificationSent(NotificationsType::returnEcontent, "aDummyUID2", NULL, "ID: ".$econtentRecordId2);
		
		$nms = new $this->entityClassName();
		$nms->setUniqueIdentifier("aDummyUID");
		$nms->setType(NotificationsTypeMailStatistics::open);
		$nms->insert();
		
		$nms->setUniqueIdentifier("aDummyUID2");
		$nms->setType(NotificationsTypeMailStatistics::rate);
		$nms->insert();	

		$nms->setUniqueIdentifier("aDummyUID");
		$nms->setType(NotificationsTypeMailStatistics::click);
		$nms->insert();
		
		$actual = $this->service->getEcontentIdActions($this->startDate, $this->endDate);
		$this->assertEquals($expected, $actual);
		
	}

	/**
	 * method getReturnEcontentTypesNumbersByRangeDate
	 * when noData
	 * should executesCorrectly
	 */
	public function test_getReturnEcontentTypesNumbersByRangeDate_noData_executesCorrectly()
	{	
		$expected[NotificationsTypeMailStatistics::click] = array("count"=>0);
		$expected[NotificationsTypeMailStatistics::open] = array("count"=>0);
		$expected[NotificationsTypeMailStatistics::review] = array("count"=>0);
		$expected[NotificationsTypeMailStatistics::rate] = array("count"=>0);
		
		$actual = $this->service->getReturnEcontentTypesNumbersByRangeDate($this->startDate, $this->endDate);
		$this->assertEquals($expected, $actual);
	}
		
	/**
	* method getReturnEcontentTypesNumbersByRangeDate 
	* when called
	* should executesCorrectly
	*/
	public function test_getReturnEcontentTypesNumbersByRangeDate_called_executesCorrectly()
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
				
		$this->insetNotificationSent(NotificationsType::returnEcontent, "aDummyUID");
		
		$actual = $this->service->getReturnEcontentTypesNumbersByRangeDate($this->startDate, $this->endDate);
		
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
	
	//Privates
	private function insetNotificationSent($type, $uniqueIdentifier, $userId = NULL, $notes = NULL)
	{
		$nms = new NotificationSent();
		$nms->setUniqueIdentifier($uniqueIdentifier);
		$nms->setType($type);
		$nms->setNotes($notes);
		$nms->setUserId($userId);
		$nms->insert();
	}

}
?>