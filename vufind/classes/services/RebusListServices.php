<?php
require_once dirname(__FILE__).'/../DAO/UserDAO.php';

interface IRebusListServices{}

class RebusListServices implements IRebusListServices
{
	private $userDAO;
	
	public function __construct(IUserDAO $userDAO = NULL)
	{
		if(!$userDAO) $userDAO = new UserDAO();
		$this->userDAO = $userDAO;
	}
	
	public function getUserInfoForRebusList($username, $password)
	{
		$user = $this->userDAO->getUserByUsernameByPassword($username, $password);
		if($user === BaseDAO::noResult())
		{
			throw new DomainException("The username/password is incorrect");
		}
		
		if(!$user->hasRebusListPrivileges())
		{
			throw new DomainException("The user has no privileges to access to RebusList");
		}
		
		$result['fullname'] = $user->firstname." ".$user->lastname;
		$result['loginname'] = $username;
		$result['email'] = $user->email;
		$result['userType'] = str_replace("rebusList-","", $user->getRebusListUserType());
		
		return $result;
		
	}
}
?>