<?php

require_once dirname(__FILE__).'/../../web/sys/Notification/NotificationMailStats.php';
require_once dirname(__FILE__).'/../../classes/notifications/NotificationsConstants.php';
require_once dirname(__FILE__).'/BaseDAO.php';

class NotificationMailStatsDAO extends BaseDAO
{
	
	public function getEntityName()
	{
		return "NotificationMailStats";
	}
	
	public function getEcontentIdActions($startDate, $endDate)
	{
		$sql = "SELECT replace(nsent.notes, \"ID: \", \"\") as econtentId, nstats.type
				FROM notifications_sent as nsent INNER JOIN notifications_mail_statistics as nstats on nsent.uniqueIdentifier= nstats.uniqueIdentifier
				WHERE 
				nsent.type = 'returnEcontent'
				AND
				nstats.timestamp >= '".$startDate."'
				AND
				nstats.timestamp <= '".$endDate."'
				ORDER BY econtentId * 1  ASC";
		
		$nms = new NotificationMailStats();
		$nms->query($sql);
		
		$results = BaseDAO::noResult();
		while ($nms->fetch())
		{
			$results[] = array($nms->econtentId, $nms->type);
		}
		return $results;
	}
	
	public function getReturnEcontentTypesNumbersByRangeDate($startDate, $endDate)
	{
		$sql = "SELECT DISTINCT(nms.type), COUNT(nms.type) as count
				FROM `notifications_mail_statistics`  as nms INNER JOIN notifications_sent as ns ON nms.uniqueIdentifier = ns.uniqueIdentifier
				WHERE
				nms.timestamp >= '".$startDate."'
				AND
				nms.timestamp <= '".$endDate."'
				AND
				ns.type= '".NotificationsType::returnEcontent."'
				GROUP BY nms.type
				ORDER BY count DESC";
		
		
		$nms = new NotificationMailStats();
		$nms->query($sql);
		
		$results[NotificationsTypeMailStatistics::click] = array('count'=>0);
		$results[NotificationsTypeMailStatistics::open] = array('count'=>0);
		$results[NotificationsTypeMailStatistics::rate] = array('count'=>0);
		$results[NotificationsTypeMailStatistics::review] = array('count'=>0);
		
		while ($nms->fetch())
		{
			$results[$nms->type] = array("count"=>$nms->count);
		}
		return $results;
	}	
	
}

?>