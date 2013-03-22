<?php
require_once dirname(__FILE__).'/../../vendors/phpmailer/class.phpmailer.php';
require_once dirname(__FILE__).'/../interfaces/IPHPMailerWrapper.php';

class PHPMailerWrapper extends PHPMailer implements IPHPMailerWrapper
{
	
}

?>