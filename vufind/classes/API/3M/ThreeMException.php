<?php

class ThreeMAPIException extends Exception
{

	protected $code = -201;

	public function __construct($message)
	{
		parent::__construct($message, $this->code);
	}
}

class ThreeMAPIItemNotFoundException extends Exception
{

	protected $code = -202;

	public function __construct($itemId)
	{
		parent::__construct("The itemId ".$itemId." was not found", $this->code);
	}
}


?>