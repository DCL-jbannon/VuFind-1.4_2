<?php
require_once dirname(__FILE__).'/../../web/sys/Notification/NotificationSent.php';

interface INotificationUtils{}

class NotificationUtils implements INotificationUtils
{
	public static function isValidNotificationUID($notuid, INotificationSent $notificationSent = NULL)
	{
		if(!$notificationSent) $notificationSent = new NotificationSent();
		
		$notificationSent->setUniqueIdentifier($notuid);
		if($notificationSent->find(true))
		{
			return true;
		}
		return false;
	}
}
?>