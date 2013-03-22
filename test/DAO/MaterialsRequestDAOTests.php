<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/MaterialsRequestDAO.php';
require_once dirname(__FILE__).'/../../vufind/classes/notifications/NotificationsConstants.php';

class MaterialsRequestDAOTests extends DAOTests
{

	/**
	* method countNumberOfRequestsMadeThisWeekByUser 
	* when noRequestMadeByUser
	* should returnZero
	*/
	public function test_countNumberOfRequestsMadeThisWeekByUser_noRequestMadeByUser_returnZero()
	{
		$expected = 0;
		$userId = "11234"; //aDummyUserId
		$actual = $this->service->countNumberOfRequestsMadeThisWeekByUser($userId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method countNumberOfRequestsMadeThisWeekByUser
	 * when anotherUserHasMadeRequest
	 * should returnZero
	 */
	public function test_countNumberOfRequestsMadeThisWeekByUser_anotherUserHasMadeRequest_returnZero()
	{
		
		$expected = 0;
		$userId = "11234"; //aDummyUserId
		$this->insertMaterialRequest(1);
		
		$actual = $this->service->countNumberOfRequestsMadeThisWeekByUser($userId);
		$this->assertEquals($expected, $actual);
	}
	
   /**
	* method countNumberOfRequestsMadeThisWeekByUser
	* when userHasMadeSomeRequest
	* should executesCorrectly
	*/
	public function test_countNumberOfRequestsMadeThisWeekByUser_userHasMadeSomeRequest_returnZero()
	{
		$expected = 2;
		$userId = "11234"; //aDummyUserId
		
		$this->insertMaterialRequest($userId);
		$this->insertMaterialRequest($userId);
		
		$actual = $this->service->countNumberOfRequestsMadeThisWeekByUser($userId);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method countNumberOfRequestsMadeThisWeekByUser
	 * when userHasMadeSomeRequestLastWeek
	 * should executesCorrectly
	 */
	public function test_countNumberOfRequestsMadeThisWeekByUser_userHasMadeSomeRequestLastWeek_returnZero()
	{
		$expected = 2;
		$userId = "11234"; //aDummyUserId
	
		$this->insertMaterialRequest($userId, mktime() - (86400  * 7)); //LastWeek
		$this->insertMaterialRequest($userId);
		$this->insertMaterialRequest($userId);
	
		$actual = $this->service->countNumberOfRequestsMadeThisWeekByUser($userId);
		$this->assertEquals($expected, $actual);
	}
	
	
	private function insertMaterialRequest($userId, $dateCreated = NULL)
	
	{
		if(!$dateCreated) $dateCreated = mktime();
		
		$mr = new $this->entityClassName();
		$mr->createdBy = $userId;
		$mr->dateCreated = $dateCreated;
		$mr->insert();
	}
	
	public function getNameDAOClass()
	{
		return 'MaterialsRequestDAO';
	}
	
	public function getEntityClassName()
	{
		return 'MaterialsRequest';	
	}
	
	public function getObjectToInsert()
	{
		$mr = new $this->entityClassName();
		return $mr;
	}
	
	public function getTablesToTruncate()
	{
		return array("materials_request");
	}
}
?>