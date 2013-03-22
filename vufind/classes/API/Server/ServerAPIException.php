<?php

class AuthorizationHeaderNotFoundException extends Exception{
	const code = -100;
	public function __construct(){
		$this->message = "The Authorization header is not present";
		$this->code = self::code;
	}
}

class AccessTokenHeaderNotFoundException extends Exception{
	const code = -101;
	public function __construct(){
		$this->message = "The AccessToken header is not present";
		$this->code = self::code;
	}
}

class MethodHeaderNotFoundException extends Exception{
	const code = -102;
	public function __construct(){
		$this->message = "The Method header is not present";
		$this->code = self::code;
	}
}

class LoginNotValidException extends Exception{
	const code = -103;
	public function __construct($message = NULL){
		$this->message = "The credentials to log in are not valid: ".$message;
		$this->code = self::code;
	}
}

class AccessTokenNotValidException extends Exception{
	const code = -104;
	public function __construct($message = NULL){
		$this->message = "The access token is not valid";
		$this->code = self::code;
	}
}

class AccessTokenExpiredException extends Exception{
	const code = -105;
	public function __construct($message = NULL){
		$this->message = "The access token has expired";
		$this->code = self::code;
	}
}


//
class APIMethodNotExistsException extends Exception{
	const code = -200;
	public function __construct($message = NULL){
		$this->message = "The method is not valid: ".$message;
		$this->code = self::code;
	}
}
?>