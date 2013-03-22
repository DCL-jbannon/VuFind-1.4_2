<?php

require_once dirname(__FILE__).'/PHPMailerWrapper.php';

class MailServices
{
	private $mailWrapper;
	
	private $errorMessage = "";
	private $smtpAddress;
	
	public function __construct(IPHPMailerWrapper $mailWrapper = NULL)
	{
		global $configArray;
		
		if(!$mailWrapper) $mailWrapper = new PHPMailerWrapper();
		$this->mailWrapper = $mailWrapper;
		
		$this->smtpAddress = $configArray['Notifications']['SMTPAddress'];
	}
	
	public function sendMailSMTPNoAuth($emailTo, $nameTo, $emailFrom, $nameFrom, $subject, $html, $plain)
	{
		global $configArray;
		
		$this->mailWrapper->SMTPDebug = 1;
		$this->mailWrapper->Host = $configArray['Notifications']['SMTPAddress'];
		$this->mailWrapper->IsSMTP();
		$this->mailWrapper->Subject = $subject;
		$this->mailWrapper->AltBody = $plain;
		$this->mailWrapper->MsgHTML($html);
		$this->mailWrapper->AddAddress($emailTo, $nameTo);
		$this->mailWrapper->SetFrom($emailFrom, $nameFrom);
				
		if(!$this->mailWrapper->Send())
		{
			$this->errorMessage = $this->mailWrapper->ErrorInfo;
			return false;
		}

		return true;
	}
	
	
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
	
}

?>