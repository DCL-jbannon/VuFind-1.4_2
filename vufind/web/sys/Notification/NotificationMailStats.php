<?php
require_once dirname(__FILE__).'/../../../classes/notifications/NotificationsConstants.php';

require_once 'DB/DataObject.php';
interface INotificationMailStats{}

class NotificationMailStats extends DB_DataObject implements INotificationMailStats
{
	public $__table = 'notifications_mail_statistics';
	public $id;
	public $type; //click, open, review, rate
	public $uniqueIdentifier;
	public $timestamp; //Default is CURRENT_TIMESTAMP
	
	/* Static get */
	function staticGet($k,$v=NULL) 
	{
		return DB_DataObject::staticGet('notifications_sent',$k,$v);
	}
	
	public function setType($type)
	{
		if(!in_array($type, $this->getValidTypes()))
		{
			throw new InvalidArgumentException("NotificationSent::setType The type ".$type." is not valid");
		}
		$this->type = $type;
	}
	
	public function setUniqueIdentifier($uid)
	{
		$this->uniqueIdentifier = $uid;
	}
	
	public function getUniqueIdentifier()
	{
		return $this->uniqueIdentifier;
	}
	
	
	//Privates......................
	private function getValidTypes()
	{
		return array(NotificationsTypeMailStatistics::click, 
					 NotificationsTypeMailStatistics::open,
					 NotificationsTypeMailStatistics::rate,
					 NotificationsTypeMailStatistics::review);
	}
}
?>