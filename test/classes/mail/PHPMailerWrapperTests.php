<?php

require_once dirname(__FILE__).'/../../../vufind/classes/mail/PHPMailerWrapper.php';

class PHPMailerWrapperTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
		
	public function setUp()
	{
		$this->service = new PHPMailerWrapper();
		parent::setUp();		
	}
	
	
	/**
	* method none 
	* when created
	* should extendsPHPMailerClass
	*/
	public function test_none_created_extendsPHPMailerClass()
	{
		$expected = "PHPMailer";
		$this->assertEquals($expected, get_parent_class($this->service));
	}
	
		
	
}


?>