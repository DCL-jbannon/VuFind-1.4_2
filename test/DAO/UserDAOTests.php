<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/UserDAO.php';
require_once dirname(__FILE__).'/../mother/UserMother.php';

class UserDAOTests extends DAOTests
{	
	private $userMother;
	
	public function setUp()
	{
		parent::setUp();
		$this->userMother = new UserMother();
	}
	
	/**
	* method getUserByUsernameByPassword 
	* when userNotFound
	* should returnEmptyResult
	*/
	public function test_getUserByUsernameByPassword_userNotFound_returnEmptyResult()
	{
		$expected = BaseDAO::noResult();
		$user = $this->userMother->getUser("aDummyUsername", "aDummyPassword");
		
		$actual = $this->service->getUserByUsernameByPassword("username", "password");
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getUserByUsernameByPassword
	 * when userFound
	 * should returnUserEntity
	 */
	public function test_getUserByUsernameByPassword_userFound_returnUserEntity()
	{
		$expected = $user = $this->userMother->getUser();
		$this->service->insert($user);
		
		$actual = $this->service->getUserByUsernameByPassword(UserMother::username, UserMother::password);
		
		$this->assertEquals("User", get_class($actual));
		$this->assertEquals($expected->username, $actual->username);
		$this->assertEquals($expected->password, $actual->password);
		$this->assertEquals("0000-00-00 00:00:00", $actual->created);
	}
	
	public function getObjectToInsert()
	{
		return $this->userMother->getUser();
	}
	
	public function getNameDAOClass()
	{
		return "UserDAO";
	}
	
	public function getEntityClassName()
	{
		return "User";
	}
	
	public function getTablesToTruncate()
	{
		return array("user");
	}
}
?>