<?php
require_once dirname(__FILE__).'/../../web/sys/Notification/NotificationSent.php';
require_once dirname(__FILE__).'/../../classes/notifications/NotificationsConstants.php';
require_once dirname(__FILE__).'/BaseDAO.php';

class NotificationSentDAO extends BaseDAO
{
	public function getEntityName()
	{
		return "NotificationSent";
	}
	
	public function getTotalSentReturnEcontentByRangeDate($startDate, $endDate)
	{
		$ns = new NotificationSent();
		$ns->selectAdd('COUNT(id) as totalSent');
		$ns->whereAdd("timestamp >= '".$startDate."'");
		$ns->whereAdd("timestamp <= '".$endDate."'");
		$ns->whereAdd("type = '".NotificationsType::returnEcontent."'");
		$ns->find(true);
		return $ns->totalSent;
	}

}
?>