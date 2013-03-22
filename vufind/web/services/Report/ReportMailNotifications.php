<?php
require_once 'services/Report/Report.php';
require_once dirname(__FILE__).'/../../../classes/DAO/NotificationSentDAO.php';
require_once dirname(__FILE__).'/../../../classes/DAO/NotificationMailStatsDAO.php';
require_once dirname(__FILE__).'/../../../classes/notifications/NotificationsConstants.php';

class ReportMailNotifications extends Report
{
	public function launch()
	{
		global $configArray;
		global $interface;
		global $user;
		
		$startDate = date("m/d/Y", mktime()-86400*7);
		$endDate = date("m/d/Y", mktime()-86400);
		
		$interface->assign("msgError", '');
		$interface->assign("startDate", $startDate);
		$interface->assign("endDate", $endDate);
		
		if (isset($_POST) && !empty($_POST))
		{
			if(empty($_POST['dateFilterStart']) || empty($_POST['dateFilterEnd']))
			{
				$interface->assign("msgError", "Please, select 'Start Date' and 'End Date'");
			}
			else
			{
				$startDate = $_POST['dateFilterStart'];
				$endDate = $_POST['dateFilterEnd'];
			}
		}
		
		$interface->assign("startDate", $startDate);
		$interface->assign("endDate", $endDate);
		
		$startDateSQL = $this->getSQLDateFormat($startDate)." 00:00:00";
		$endDateSQL = $this->getSQLDateFormat($endDate)." 23:59:59";
		
		$naDAO = new NotificationSentDAO();
		$totalNotificationSent = $naDAO->getTotalSentReturnEcontentByRangeDate($startDateSQL, $endDateSQL);
		
		$nms = new NotificationMailStatsDAO();
		$totalOpenClicks = $nms->getReturnEcontentTyopesNumbersByRangeDate($startDateSQL, $endDateSQL);
		
		$interface->assign("totalMailSent", intval($totalNotificationSent));
		$interface->assign("totalClicks", intval($totalOpenClicks[NotificationsTypeMailStatistics::click]['count']));
		$interface->assign("totalOpen", intval($totalOpenClicks[NotificationsTypeMailStatistics::open]['count']));
		$interface->assign("totalRate", intval($totalOpenClicks[NotificationsTypeMailStatistics::rate]['count']));
		$interface->assign("totalReview", intval($totalOpenClicks[NotificationsTypeMailStatistics::review]['count']));
		
		$interface->setPageTitle('Report - Mails Notification');
		$interface->setTemplate('mails-notifications.tpl');
		$interface->display('layout.tpl');
	}
	
	private function getSQLDateFormat($date)
	{
		$partsDate = explode("/", $date);
		return $partsDate[2]."-".$partsDate[0]."-".$partsDate[1];
	}
}