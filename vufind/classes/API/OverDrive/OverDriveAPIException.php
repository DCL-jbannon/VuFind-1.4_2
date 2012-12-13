<?php

class OverDriveAPIException extends Exception{
	protected $code = -100;
}


class OverDriveHttpResponseException extends Exception{

	protected $code = -101;

	public function __construct($responseCode)
	{
		parent::__construct("OverDriveHttpResponseException: Bad Http Response. Reponse Code: ".$responseCode, $this->code);
	}
}


class OverDriveTokenExpiredException extends Exception{
	
	protected $code = -102;

	public function __construct()
	{
		parent::__construct("Access Token has expired", $this->code);
	}
	
}

?>