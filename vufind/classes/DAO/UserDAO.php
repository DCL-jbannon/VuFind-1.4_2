<?php
require_once dirname(__FILE__).'/../../web/services/MyResearch/lib/User.php';
require_once dirname(__FILE__).'/BaseDAO.php';

interface IUserDAO{}

class UserDAO extends BaseDAO implements IUserDAO
{

	public function getEntityName()
	{
		return "User";
	}
	
	public function getUserByUsernameByPassword($username, $password)
	{
		$user = new User();
		$user->username = $username;
		$user->password = $password;
		if($user->find(true))
		{
			return $user;
		}
		else
		{
			return BaseDAO::noResult();
		}
	}
	
}
?>