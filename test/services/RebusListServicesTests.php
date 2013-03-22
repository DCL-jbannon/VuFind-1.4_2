<?php
require_once dirname(__FILE__).'/../../vufind/classes/services/RebusListServices.php';

class RebusListServicesTests extends PHPUnit_Framework_TestCase
{
	private $userMock;
	private $userDAOMock;
	private $service;
		
	public function setUp()
	{
		$this->userMock = $this->getMock("IUser", array("hasRebusListPrivileges", "getRebusListUserType"));
		$this->userDAOMock = $this->getMock("IUserDAO", array("getUserByUsernameByPassword"));
		
		$this->service = new RebusListServices($this->userDAOMock);
		parent::setUp();		
	}

	/**
	* method getUserInfoForRebusList 
	* when userNotFound
	* should throw
	* @expectedException DomainException
	*/
	public function test_getUserInfoForRebusList_userNotFound_throw()
	{
		$this->prepareUserDAO(BaseDAO::noResult());
		$this->service->getUserInfoForRebusList("aNonvalidUSername", "aNonvalidPassword");
	}
	
	/**
	 * method getUserInfoForRebusList
	 * when userHasNoRebusPrivileges
	 * should throw
	 * @expectedException DomainException
	 */
	public function test_getUserInfoForRebusList_userHasNoRebusPrivileges_throw()
	{
		$this->prepareUserDAO($this->userMock);
		$this->prepareHasRebusList(false);
		
		$this->service->getUserInfoForRebusList("aNonvalidUSername", "aNonvalidPassword");
	}
	
	/**
	 * method getUserInfoForRebusList
	 * when called
	 * should executesCorrectly
	 */
	public function test_getUserInfoForRebusList_called_executesCorrectly()
	{
		$username = "aDummyUsername";
		$password = "aDummyPassword";
		
		$this->userMock->firstname = "aDummyFirstName";
		$this->userMock->lastname = "aDummyLastName";
		$this->userMock->email = "aDummyEmail";
		
		$expected['fullname'] = $this->userMock->firstname." ".$this->userMock->lastname;
		$expected['loginname'] = $username;
		$expected['email'] = $this->userMock->email;
		$expected['userType'] = "aDummyRebusListUserType";
		
		$this->prepareUserDAO($this->userMock);
		$this->prepareHasRebusList(true);
		
		$this->userMock->expects($this->once())
						->method("getRebusListUserType")
						->will($this->returnValue("rebusList-aDummyRebusListUserType"));
	
		$actual = $this->service->getUserInfoForRebusList($username, $password);
		$this->assertEquals($expected, $actual);
	}
	
	//PREPARES
	private function prepareUserDAO($result)
	{
		$this->userDAOMock->expects($this->once())
							->method("getUserByUsernameByPassword")
							->will($this->returnValue($result));
	}
	
	private function prepareHasRebusList($result)
	{
		$this->userMock->expects($this->once())
						->method("hasRebusListPrivileges")
						->will($this->returnValue($result));
	}
	
}
?>