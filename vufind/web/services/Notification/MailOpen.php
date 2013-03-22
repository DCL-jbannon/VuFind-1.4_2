<?php
require_once 'Action.php';
require_once dirname(__FILE__).'/../../../classes/services/NotificationServices.php';
require_once dirname(__FILE__).'/../../../classes/notifications/NotificationsConstants.php';

class MailOpen extends Action
{
	public function launch()
	{
		$this->generateGifImage();
	}
	
	private function generateGifImage()
	{
		$im = imagecreate(1,1);
		$background_color = imagecolorallocate($im, 255, 255, 255);
		header("Status: 200");
		header('Content-Type: image/gif');
		imagegif($im);
		exit(0);
	}
}
?>