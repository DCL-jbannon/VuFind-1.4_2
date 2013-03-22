<?php
require_once dirname(__FILE__).'/../../web/sys/Notification/NotificationMailStats.php';
require_once dirname(__FILE__).'/../notifications/NotificationsConstants.php';
require_once dirname(__FILE__).'/../Utils/NotificationUtils.php';


class NotificationServices
{

	public function insertMailStats($notuid, $module, $action, $method = NULL, INotificationUtils $notificationUtils = NULL,
									INotificationMailStats $notiMailStat = NULL)
	{
		
		if(!$notificationUtils) $notificationUtils = new NotificationUtils();
		if(!$notiMailStat) $notiMailStat = new NotificationMailStats();
		
		if($notificationUtils->isValidNotificationUID($notuid))
		{
			if($this->isOpeningMail($module, $action))
			{
				$type = NotificationsTypeMailStatistics::open;
			}
			elseif ($this->isRating($module, $action, $method))
			{
				$type = NotificationsTypeMailStatistics::rate;
			}
			elseif ($this->isReviewing($module, $action, $method))
			{
				$type = NotificationsTypeMailStatistics::review;
			}
			else
			{
				$type = NotificationsTypeMailStatistics::click;
			}
			
			$notiMailStat->setUniqueIdentifier($notuid);
			$notiMailStat->setType($type);
			$notiMailStat->insert();
		}
	}
	
	
	private function isRating($module, $action, $method)
	{
		return ( ($module = "EcontentRecord") && ($action == "AJAX") && ($method == "RateTitle"));
	}
	
	private function isReviewing($module, $action, $method)
	{
		return ( ($module = "EcontentRecord") && ($action == "AJAX") && ($method == "SaveComment"));
	}
	
	private function isOpeningMail($module, $action)
	{
		return ( ($module = "Notification") && ($action == "MailOpen") );
	}
	
}
?>