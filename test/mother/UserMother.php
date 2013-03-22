<?php
require_once dirname(__FILE__).'/../../vufind/web/services/MyResearch/lib/User.php';

class UserMother
{
	const username = "aDummyUsername";
	const password = "aDummyPassword";
	
	public function getUser($username = NULL, $password = NULL)
	{
		if(!$username) $username = self::username;
		if(!$password) $password = self::password;
		
		$user = new User();
		$user->username = $username;
		$user->password = $password;
		
		return $user;
	}
	
}
?>