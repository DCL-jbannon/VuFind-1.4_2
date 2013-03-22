<?php
require_once dirname(__FILE__).'/../../../vufind/classes/notifications/ReturnEcontentNotification.php';
require_once dirname(__FILE__).'/../../../vufind/classes/notifications/NotificationsConstants.php';

class ReturnEcontentNotificationTests extends PHPUnit_Framework_TestCase
{
	private $userMock;
	private $econtentRecordMock;
	
	private $mailerServicesMock;
	private $DBUserUtilsMock;
	private $DBEcontentUtilsMock;
	private $interfaceMock;
	private $bookcoverURLMock;
	private $notificationsSentMock;
	
	private $service;
	
	const userId = "aDummyUserId";
	const eContentRecordId = "aDummyEContentRecordId";
	const lastname = "aDummyLastName";
	const firstname = "aDummyFirstName";
	const email = "aDummyEmail";
	
	const title = "aDummyTitle";
	const author = "aDummyAuthor";
	const uniqueIdentifier = 'aDummyUniqueIdentifier';
	
	const htmlStringTemplage = "aDummyHTMLString";
	const plainStringTemplage = "aDummyPlainString";
	
	const bookCoverUrl = "aDummyBookCoverUrl";
		
	public function setUp()
	{
		$this->logger = $this->getMock("aDummyLoggerClass", array("log"));
		
		$this->econtentRecordMock = $this->getMock("IEContentRecord");
		$this->userMock = $this->getMock("IUser");
		
		$this->mailerServicesMock = $this->getMock("IMailServices", array("sendMailSMTPNoAuth", "getErrorMessage"));
		$this->DBUserUtilsMock = $this->getMock("IDB_DataObjectUtils", array("getObjectById"));
		$this->DBEcontentUtilsMock = $this->getMock("IDB_DataObjectUtils", array("getObjectById"));
		$this->interfaceMock = $this->getMock("IUInterface", array("fetch", "assign"));
		$this->bookcoverURLMock = $this->getMock("IBookcoverURL", array("getBookCoverUrl"));
		$this->notificationsSentMock = $this->getMock("INotificationSent", array("setUniqueIdentifier", "setType", "setUserId", "insert", "setNotes"));
		
		$this->service = new ReturnEcontentNotification($this->DBUserUtilsMock,
														$this->DBEcontentUtilsMock,
														$this->interfaceMock,
														$this->mailerServicesMock,
														$this->bookcoverURLMock,
														$this->notificationsSentMock
													   );
		parent::setUp();
	}
	
	/**
	* method sendNotification 
	* when userIdDoesNotExists
	* should returnFalse
	*/
	public function test_sendNotification_userIdDoesNotExists_returnFalse()
	{
		$this->prepareDBUtilsMockForUser(false);
		
		$actual = $this->service->sendNotification(self::userId, self::eContentRecordId, self::uniqueIdentifier);
		$this->assertFalse($actual);
	}
	
	/**
	 * method sendNotification
	 * when econtentRecordIdDoesNotExists
	 * should returnFalse
	 */
	public function test_sendNotification_econtentRecordIdDoesNotExists_returnFalse()
	{
		$this->prepareDBUtilsMockForUser($this->userMock);
		$this->prepareDBUtilsMockForEcontentRecord(false);
	
		$actual = $this->service->sendNotification(self::userId, self::eContentRecordId, self::uniqueIdentifier);
		$this->assertFalse($actual);
	}
	
	/**
	 * method sendNotification
	 * when called
	 * should returnTrue
	 */
	public function test_sendNotification_called_returnTrue()
	{
		global $configArray, $logger;
		$logger = $this->logger;
		
		$configArray['Site']['url'] = "http:://aDummyUrl.org";
		$configArray['Notifications']['subjectReviewRate'] = "aDummySubject [subject] ????";
		$configArray['Notifications']['fromNameReviewRate'] = "aDummyfromName";
		$configArray['Notifications']['fromEmailReviewRate'] = "aDummyfromEmail";
		
		$variablesToSmarty = $this->getVariablesSmarty($configArray);
		
		$this->prepareUserMock();
		$this->prepareEcontentRecordMock();
		
		$this->prepareBookCoverUrlMock();
		$this->prepareInterfaceMock($variablesToSmarty);
		
		$this->prepareMailMock($configArray);
		$this->prepareNotificationSentMock();
		
		$this->prepareDBUtilsMockForUser($this->userMock);
		$this->prepareDBUtilsMockForEcontentRecord($this->econtentRecordMock);
	
		$actual = $this->service->sendNotification(self::userId, self::eContentRecordId, self::uniqueIdentifier);
		$this->assertTrue($actual);
	}
	
	//Prepares and Exercises
	private function prepareMailMock($configArray)
	{
		$this->mailerServicesMock->expects($this->once())
								->method("sendMailSMTPNoAuth")
								->with($this->equalTo(self::email), $this->equalTo(self::firstname),
										$this->equalTo($configArray['Notifications']['fromEmailReviewRate']),
										$this->equalTo($configArray['Notifications']['fromNameReviewRate']),
										$this->equalTo("aDummySubject ".self::title." ????"),
										$this->equalTo(self::htmlStringTemplage), $this->equalTo(self::plainStringTemplage)
									   )
								->will($this->returnValue(true));
	}
	
	
	private function prepareNotificationSentMock()
	{
		$this->notificationsSentMock->expects($this->once())
									->method("setType")
									->with($this->equalTo(NotificationsType::returnEcontent));
		
		$this->notificationsSentMock->expects($this->once())
									->method("setUniqueIdentifier")
									->with($this->equalTo(self::uniqueIdentifier));
		
		$this->notificationsSentMock->expects($this->once())
									->method("setUserId")
									->with($this->equalTo(self::userId));
		
		$this->notificationsSentMock->expects($this->once())
									->method("setNotes")
									->with($this->equalTo("ID: ".$this->econtentRecordMock->id));
		
		$this->notificationsSentMock->expects($this->once())
									->method("insert");
	}
	
	
	private function prepareUserMock()
	{
		$this->userMock->id = self::userId;
		$this->userMock->lastname = self::lastname;
		$this->userMock->firstname = self::firstname;
		$this->userMock->email = self::email;
	}
	
	private function prepareEcontentRecordMock()
	{
		$this->econtentRecordMock->id = self::eContentRecordId;
		$this->econtentRecordMock->title = self::title;
		$this->econtentRecordMock->author = self::author;
	}
	
	private function prepareBookCoverUrlMock()
	{
		$this->bookcoverURLMock->expects($this->once())
								->method("getBookCoverUrl")
								->with($this->equalTo("large"), $this->equalTo(""), $this->equalTo(self::eContentRecordId), $this->equalTo(true))
								->will($this->returnValue(self::bookCoverUrl));
	}
	
	private function prepareInterfaceMock($variablesToSmarty)
	{
		$this->interfaceMock->expects($this->once())
							->method("assign")
							->with($this->equalTo($variablesToSmarty));
		
		$this->interfaceMock->expects($this->at(1))
							->method("fetch")
							->with($this->equalTo("Notifications/reviewRateHTML.tpl"))
							->will($this->returnValue(self::htmlStringTemplage));
		
		$this->interfaceMock->expects($this->at(2))
							->method("fetch")
							->with($this->equalTo("Notifications/reviewRatePlainText.tpl"))
							->will($this->returnValue(self::plainStringTemplage));
	}
	
	private function prepareDBUtilsMockForUser($returnValue)
	{
		$this->DBUserUtilsMock->expects($this->once())
								->method("getObjectById")
								->with($this->equalTo(self::userId))
								->will($this->returnValue($returnValue));
	}
	
	private function prepareDBUtilsMockForEcontentRecord($returnValue)
	{
		$this->DBEcontentUtilsMock->expects($this->once())
									->method("getObjectById")
									->with($this->equalTo(self::eContentRecordId))
									->will($this->returnValue($returnValue));
	}
	
	private function getVariablesSmarty($configArray)
	{		
		$variablesToSmarty["notification_lastName"] = self::lastname;
		$variablesToSmarty["notification_firstName"] = self::firstname;
		$variablesToSmarty["notification_titleLink"] = $configArray['Site']['url'].'/EcontentRecord/'.self::eContentRecordId.'?notuid='.self::uniqueIdentifier;
		$variablesToSmarty["notification_bookcoverUrl"] = self::bookCoverUrl;
		$variablesToSmarty["notification_title"] = self::title;
		$variablesToSmarty["notification_author"] = self::author;
		$variablesToSmarty["notification_baseUrl"] = $configArray['Site']['url'];
		$variablesToSmarty["notification_patronEmail"] = self::email;
		$variablesToSmarty["notification_notuid"] = self::uniqueIdentifier;
		return $variablesToSmarty;
	}
	
}

?>