<?php
require_once 'DB/DataObject.php';
interface INotificationSent{}

class NotificationSent extends DB_DataObject implements INotificationSent
{
	public $__table = 'notifications_sent';
	public $id;
	public $type; // [returnEcontent]
	public $uniqueIdentifier;
	public $userId;
	public $notes;
	public $timestamp; //Default is CURRENT_TIMESTAMP
	
	/* Static get */
	function staticGet($k,$v=NULL) 
	{
		return DB_DataObject::staticGet('notifications_sent',$k,$v);
	}
	
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}
	
	public function getNotes()
	{
		return $this->notes;
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
	
	private function getValidTypes()
	{
		return array('returnEcontent');
	}
	
	public function getUserId()
	{
		return $this->userId;
	}
	
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}
	
}
?>