<?php
require_once dirname(__FILE__).'/../interfaces/IPHPMailerWrapper.php';
require_once dirname(__FILE__).'/../interfaces/IUInterface.php';
require_once dirname(__FILE__).'/../interfaces/IEContentRecord.php';
require_once dirname(__FILE__).'/../Utils/DB_DataObject/UserDBUtils.php';
require_once dirname(__FILE__).'/../Utils/DB_DataObject/EcontentRecordDBUtils.php';
require_once dirname(__FILE__).'/../../web/sys/Interface.php';
require_once dirname(__FILE__).'/../mail/MailServices.php';
require_once dirname(__FILE__).'/../Utils/BookCoverURL.php';
require_once dirname(__FILE__).'/../../web/sys/Notification/NotificationSent.php';
require_once dirname(__FILE__).'/../../classes/notifications/NotificationsConstants.php';

class ReturnEcontentNotification
{	
	private $userDBUtils;
	private $econtentRecordDBUtils;
	private $mailServices;
	private $interface;
	private $bookCoverUrl;
	private $notificationSent;
	
	public function __construct(IDB_DataObjectUtils $userDBUtils = NULL,
								IDB_DataObjectUtils $eContentRecordDBUtils = NULL,
								IUInterface $interface = NULL,
								IMailServices $mailServices = NULL,
								IBookcoverURL $bookCoverUrl = NULL, 
								INotificationSent $notificationSent = NULL)
	{
		if(!$userDBUtils) $userDBUtils = new UserDBUtils();
		$this->userDBUtils = $userDBUtils;
		
		if(!$eContentRecordDBUtils) $eContentRecordDBUtils = new EcontentRecordDBUtils();
		$this->econtentRecordDBUtils = $eContentRecordDBUtils;
		
		if(!$interface) $interface = new UInterface();
		$this->interface = $interface;
		
		if(!$mailServices) $mailServices = new MailServices();
		$this->mailServices = $mailServices;
		
		if(!$bookCoverUrl) $bookCoverUrl = new BookcoverURL();
		$this->bookCoverUrl = $bookCoverUrl;
		
		if(!$notificationSent) $notificationSent = new NotificationSent();
		$this->notificationSent = $notificationSent;
	}
	
	
	public function sendNotification($userId, $econtentRecordId, $uniqueIdentifier)
	{
		global $configArray, $logger;
		
		/** @var $user User */
		$user = $this->userDBUtils->getObjectById($userId);
		if($user === false)
		{
			return false;
		}
		
		/** @var $user EContentRecord */
		$econtentRecord = $this->econtentRecordDBUtils->getObjectById($econtentRecordId);
		if($econtentRecord === false)
		{
			return false;
		}
		
		$variablesToTemplate = array();
		$variablesToTemplate["notification_lastName"] = $user->lastname;
		$variablesToTemplate["notification_firstName"] = $user->firstname;
		$variablesToTemplate["notification_titleLink"] = $configArray['Site']['url'].'/EcontentRecord/'.$econtentRecord->id."?notuid=".$uniqueIdentifier;
		$variablesToTemplate["notification_bookcoverUrl"] = $this->bookCoverUrl->getBookCoverUrl("large", "", $econtentRecord->id, true);
		$variablesToTemplate["notification_title"] = $econtentRecord->title;
		$variablesToTemplate["notification_author"] = $econtentRecord->author;
		$variablesToTemplate["notification_baseUrl"] = $configArray['Site']['url'];
		$variablesToTemplate["notification_patronEmail"] = $user->email;
		$variablesToTemplate["notification_notuid"] = $uniqueIdentifier;
		$this->interface->assign($variablesToTemplate);
		
		$html =  $this->interface->fetch("Notifications/reviewRateHTML.tpl");
		$plain = $this->interface->fetch("Notifications/reviewRatePlainText.tpl");
		
		$subject = str_replace("[subject]", $econtentRecord->title, $configArray['Notifications']['subjectReviewRate']);
		
		if (!$this->mailServices->sendMailSMTPNoAuth($user->email, $user->firstname, 
													 $configArray['Notifications']['fromEmailReviewRate'],
													 $configArray['Notifications']['fromNameReviewRate'],
													 $subject, $html, $plain))
		{
			@$logger->log($this->mailServices->getErrorMessage(), PEAR_LOG_ERR);
		}
		else
		{
			$this->notificationSent->setType(NotificationsType::returnEcontent);
			$this->notificationSent->setUserId($user->id);
			$this->notificationSent->setUniqueIdentifier($uniqueIdentifier);
			$this->notificationSent->setNotes("ID: ".$econtentRecordId);
			$this->notificationSent->insert();
		}
		return true;
	}

}
?>