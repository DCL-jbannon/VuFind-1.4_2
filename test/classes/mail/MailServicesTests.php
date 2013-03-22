<?php
require_once dirname(__FILE__).'/../../../vufind/classes/mail/MailServices.php';

class MailServicesTests extends PHPUnit_Framework_TestCase
{
	private $phpmailerWrapperMock;
	private $service;
	
	const emailFrom = "aDummyEmailFrom";
	const nameFrom = "aDummyNameFrom";
	const emailTo = "aDummtEmailTo";
	const nameTo = "aDummyNameTo";
	const subject = "aDummySubject";
	const html = "aDummyHTML";
	const plain = "aDummyPlainTextForAltBody";
	
	public function setUp()
	{
		global $configArray;

		$configArray['Notifications']['SMTPAddress'] = "aDummySMTPAddress";
		
		$this->phpmailerWrapperMock = $this->getMock("IPHPMailerWrapper", array("IsSMTP", "MsgHTML", "Send", "AddAddress", "SetFrom"));
		$this->service = new MailServices($this->phpmailerWrapperMock);
		parent::setUp();
	}
	
	
	/**
	 * method sendMailSMTPNoAuth
	 * when called
	 * should returnFalse
	 */
	public function test_sendMailSMTPNoAuth_called_returnFalse()
	{
		global $configArray;
		
		$this->phpmailerWrapperMock->ErrorInfo = "aDummyErrorMessage";
		$this->phpmailerWrapperMock->expects($this->once())
									->method("Send")
									->will($this->returnValue(false));
		
		$actual = $this->service->sendMailSMTPNoAuth(self::emailTo, self::nameTo, self::emailFrom, self::nameFrom, self::subject, self::html, self::plain);
		$this->assertFalse($actual);
		$this->assertEquals($this->phpmailerWrapperMock->ErrorInfo, $this->service->getErrorMessage());
	}
	
	/**
	* method sendMailSMTPNoAuth 
	* when called
	* should returnTrue
	*/
	public function test_sendMailSMTPNoAuth_called_returnTrue()
	{
		global $configArray;
		
		$this->phpmailerWrapperMock->expects($this->once())
								   ->method("IsSMTP");
		
		$this->phpmailerWrapperMock->expects($this->once())
									->method("MsgHTML")
									->with($this->equalTo(self::html));
		
		$this->phpmailerWrapperMock->expects($this->once())
									->method("AddAddress")
									->with($this->equalTo(self::emailTo), $this->equalTo(self::nameTo));
		
		$this->phpmailerWrapperMock->expects($this->once())
									->method("SetFrom")
									->with($this->equalTo(self::emailFrom), $this->equalTo(self::nameFrom));
		
		$this->phpmailerWrapperMock->expects($this->once())
									->method("Send")
									->will($this->returnValue(true));
		
		$actual = $this->service->sendMailSMTPNoAuth(self::emailTo, self::nameTo, self::emailFrom, self::nameFrom, self::subject, self::html, self::plain);
		
		$this->assertEquals($configArray['Notifications']['SMTPAddress'], $this->phpmailerWrapperMock->Host);
		$this->assertEquals(self::subject, $this->phpmailerWrapperMock->Subject);
		$this->assertEquals(self::plain, $this->phpmailerWrapperMock->AltBody);
		$this->assertTrue($actual);
	}
	
}

?>