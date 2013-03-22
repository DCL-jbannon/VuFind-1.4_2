<?php
require_once 'Action.php';
require_once 'services/MyResearch/lib/User.php';
require_once dirname(__FILE__).'/../../../classes/Utils/RequestUtils.php';

class RebusList extends Action
{

	function launch()
	{
		global $configArray;
		$method = RequestUtils::getGet("method");
		if($method == 'authRL')
		{
			$username = RequestUtils::getGet("username");
			$password = RequestUtils::getGet("password");
			$apiKey = RequestUtils::getGet("apiKey");
			
			if($apiKey == $configArray['RebusList']['apiKey'])
			{
				if(empty($username) || empty($password))
				{
					$result = $this->getErrorResult("parameters", "The username and the password cannot be empty");
				}
				else
				{
					$user = new User();
					$user->cat_username = $username;
					$user->password = $password;
					if($user->find(true))
					{
						if($user->hasRebusListPrivileges())
						{
							$result['fullname'] = $user->firstname." ".$user->lastname;
							$result['loginname'] = $username;
							$result['email'] = $user->email;
							$result['userType'] = str_replace("rebusList-","", $user->getRebusListUserType());
						}
						else
						{
							$result = $this->getErrorResult("perm", "The user has no privileges to use Rebus:List");
						}
					}
					else
					{
						$result = $this->getErrorResult("auth", "Authentication failed");
					}
				}
			}
			else
			{
				$result = $this->getErrorResult("apiKey", "The API Key is not valid");
			}
		}
		else
		{
			$result = $this->getErrorResult("methodName", "Method name ".$method." no valid");
		}
		
		header('Content-Type: application/json');
		header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		echo json_encode($result);
	}
	
	private function getErrorResult($code, $message)
	{
		$result['errorCode'] = $code;
		$result['errorMessage'] = $message;
		return $result;
	}
}