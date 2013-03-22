<?php

require_once dirname(__FILE__).'/../../vufind/web/services/MyResearch/lib/User.php';

class UserTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $materialsRequestDAOMock;
		
	public function setUp()
	{
		$this->materialsRequestDAOMock = $this->getMock("IMaterialsRequestDAO", array("countNumberOfRequestsMadeThisWeekByUser"));
		$this->service = new User();
		parent::setUp();		
	}
	
	/**
	* method getBarcode 
	* when called
	* should returnCorrectly
	*/
	public function test_getBarcode_called_returnCorrectly()
	{
		$expected = "aDummyCatUserName";
		$this->service->cat_username = "aDummyCatUserName";
		
		$actual = $this->service->getBarcode();
		
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method isOptInReviewNotification 
	* when itIsNot
	* should returnFalse
	*/
	public function test_isOptInReviewNotification_itIsNot_returnFalse()
	{
		$this->service->notificationReview = 0;
		$actual = $this->service->isOptInReviewNotification();
		$this->assertFalse($actual);
	}
	
	/**
	 * method isOptInReviewNotification
	 * when itIs
	 * should returnTrue
	 */
	public function test_isOptInReviewNotification_itIs_returnTrue()
	{
		$this->service->notificationReview = 1;
		$actual = $this->service->isOptInReviewNotification();
		$this->assertTrue($actual);
	}
	
	/**
	* method hasRebusListPrivileges 
	* when itHasNot
	* should returnFalse
	*/
	public function test_hasRebusListPrivileges_itHasNot_returnFalse()
	{
		$this->service->roles = array();
		$actual = $this->service->hasRebusListPrivileges();
		$this->assertFalse($actual);
	}
	
	/**
	* method hasRebusListPrivileges 
	* when itHas
	* should returnTrue
	* @dataProvider DP_getRebusListRoles
	*/
	public function test_hasRebusListPrivileges_itHas_returnTrue($role)
	{
		$this->service->roles = array($role);
		$actual = $this->service->hasRebusListPrivileges();
		$this->assertTrue($actual);
	}
	
	public function DP_getRebusListRoles()
	{
		return array(
					array(User::roleRebusListAdmin),
					array(User::roleRebusListLibrarian),
					array(User::roleRebusListStaff),
				);
	}

	
	/**
	* method getRebusListUserType 
	* when hasNotPrivilege
	* should returnEmpty
	*/
	public function test_getRebusListUserType_hasNotPrivilege_returnEmpty()
	{
		$expected = "";
		$this->service->roles = array();
		$actual = $this->service->getRebusListUserType();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Since the User can have more that One rebus:List privilege, return always the highest type privilege
	 * method getRebusListUserType
 	 * when called	
	 * should returnCorrectPrivilege
	 * @dataProvider DP_getRebusListUserType
	 */
	public function test_getRebusListUserType_called_returnEmpty($roles, $expected)
	{
		$this->service->roles = $roles;
		$actual = $this->service->getRebusListUserType();
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getRebusListUserType()
	{
		return array(
				array(array(User::roleRebusListAdmin), User::roleRebusListAdmin),
				array(array(User::roleRebusListLibrarian), User::roleRebusListLibrarian),
				array(array(User::roleRebusListStaff), User::roleRebusListStaff),
				array(array(User::roleRebusListStaff, User::roleRebusListAdmin, User::roleRebusListLibrarian), User::roleRebusListAdmin),
				array(array(User::roleRebusListLibrarian, User::roleRebusListAdmin, User::roleRebusListStaff), User::roleRebusListAdmin),
				array(array(User::roleRebusListAdmin, User::roleRebusListLibrarian, User::roleRebusListStaff), User::roleRebusListAdmin),
				array(array(User::roleRebusListAdmin, User::roleRebusListLibrarian), User::roleRebusListAdmin),
				array(array(User::roleRebusListStaff, User::roleRebusListAdmin), User::roleRebusListAdmin),
				array(array(User::roleRebusListStaff, User::roleRebusListAdmin), User::roleRebusListAdmin),
				array(array(User::roleRebusListLibrarian, User::roleRebusListStaff), User::roleRebusListLibrarian),
				array(array(User::roleRebusListStaff, User::roleRebusListLibrarian), User::roleRebusListLibrarian),
				array(array(User::roleRebusListStaff, User::roleRebusListAdmin), User::roleRebusListAdmin),
				array(array(User::roleRebusListStaff,"aDummyRole"), User::roleRebusListStaff),
				array(array(User::roleRebusListAdmin,"aDummyRole"), User::roleRebusListAdmin),
				array(array(User::roleRebusListLibrarian,"aDummyRole"), User::roleRebusListLibrarian)
		);
	}
	
	/**
	* method hasReachMaxRequestPerWeek 
	* when hasNot
	* should returnFalse
	*/
	public function test_hasReachMaxRequestPerWeek_hasNot_returnFalse()
	{
		$userId = "aDummyUserId";
		$this->service->id = $userId;
		
		$numberRequestUserHasMadeThisWeek = User::maxRequestPerWeek -2;
		
		$this->materialsRequestDAOMock->expects($this->once())
										->method("countNumberOfRequestsMadeThisWeekByUser")
										->with($this->equalTo($userId))
										->will($this->returnValue($numberRequestUserHasMadeThisWeek));
		
		$actual = $this->service->hasReachMaxRequestPerWeek($this->materialsRequestDAOMock);
		$this->assertFalse($actual);
		
	}
	
	/**
	 * method hasReachMaxRequestPerWeek
	 * when ItHas
	 * should returnFalse
	 * @dataProvider DP_hasReachMaxRequestPerWeek
	 */
	public function test_hasReachMaxRequestPerWeek_ItHas_returnFalse($numberRequestUserHasMadeThisWeek)
	{
		$userId = "aDummyUserId";
		$this->service->id = $userId;
	
		$this->materialsRequestDAOMock->expects($this->once())
										->method("countNumberOfRequestsMadeThisWeekByUser")
										->with($this->equalTo($userId))
										->will($this->returnValue($numberRequestUserHasMadeThisWeek));
	
		$actual = $this->service->hasReachMaxRequestPerWeek($this->materialsRequestDAOMock);
		$this->assertTrue($actual);
	
	}
	
	public static function  DP_hasReachMaxRequestPerWeek() 
	{
		return array(
						array(User::maxRequestPerWeek),
						array(User::maxRequestPerWeek+1),
					);
	}
	
}
?>