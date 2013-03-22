<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/NotificationSentDAO.php';
require_once dirname(__FILE__).'/../../vufind/classes/notifications/NotificationsConstants.php';

class NotificationSentDAOTests extends DAOTests
{
	
	/**
	 * method getTotalSentReturnEcontentByRangeDate
	 * when called
	 * should executesCorrectly
	 */
	public function test_getTotalSentReturnEcontentByRangeDate_called_executesCorrectly()
	{
		
		$expected = 2;
		$nms = new NotificationSent();
		$nms->setUniqueIdentifier("aDummyUID");
		$nms->setType(NotificationsType::returnEcontent);
		$nms->insert();
		$nms->insert();
	
		$startDate = date("Y-m-d")." 00:00:00";
		$endDate = date("Y-m-d")." 23:59:59";
		
		$actual = $this->service->getTotalSentReturnEcontentByRangeDate($startDate, $endDate);
		$this->assertEquals($expected, $actual);
	}
	
	public function getObjectToInsert()
	{
		$ns = new $this->entityClassName();
		$ns->setUniqueIdentifier("aDummyUID");
		$ns->setUserId(12);
		$ns->setType(NotificationsType::returnEcontent);
		$ns->setNotes('aDummyNotes');
		return $ns;
	}
	
	public function getTablesToTruncate()
	{
		return array("notifications_sent");
	}
	
	public function getNameDAOClass()
	{
		return 'NotificationSentDAO';
	}
	
	public function getEntityClassName()
	{
		return 'NotificationSent';
	}

}
?>