<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/NotificationUtils.php';

class NotificationUtilsTests extends PHPUnit_Framework_TestCase
{
	
	private $notificationSentMock;
	
	public function setUp()
	{
		$this->notificationSentMock = $this->getMock("INotificationSent", array("setUniqueIdentifier", "find"));
	}
	
	/**
	* method isValidNotificationUID 
	* when notUidIsNotValid
	* should returnFalse
	*/
	public function test_isValidNotificationUID_notUidIsNotValid_returnFalse()
	{
		$notuid = "aDummyNotificationUID";
		$this->prepareNotificationSentMock($notuid, false);
		
		$actual = NotificationUtils::isValidNotificationUID($notuid, $this->notificationSentMock);
		$this->assertFalse($actual);
	}
	
	/**
	 * method isValidNotificationUID
	 * when notUidIsValid
	 * should returnFalse
	 */
	public function test_isValidNotificationUID_notUidIsValid_returnFalse()
	{
		$notuid = "aDummyNotificationUID";
		$_REQUEST['notuid'] = $notuid;
		$this->prepareNotificationSentMock($notuid, true);
		
		$actual = NotificationUtils::isValidNotificationUID($notuid, $this->notificationSentMock);
		$this->assertTrue($actual);
	}		

	//prepares
	private function prepareNotificationSentMock($notuid, $returnValue)
	{
		$this->notificationSentMock->expects($this->once())
									->method("setUniqueIdentifier")
									->with($this->equalTo($notuid));
		
		$this->notificationSentMock->expects($this->once())
									->method("find")
									->with($this->equalTo(true))
									->will($this->returnValue($returnValue));
	}
		
	
		
	
	
}



?>