<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/ResourceDAO.php';

class ResourceDAOTests extends DAOTests
{	

	/**
	 * method getByRecordId
	 * when called
	 * should executesCorrectly
	 */
	public function test_getByRecordId_called_executesCorrectly()
	{
		$recordId = "456";
		$object = $this->getObjectToInsert();
		$object->record_id = $recordId;
		$this->service->insert($object);
	
		$dao = $this->getNameDAOClass();
		$object2 = new $dao();
	
		$actual = $object2->getByRecordId($recordId);
		$this->assertEquals(1, $actual->id);
		$this->assertEquals($recordId, $actual->record_id);
	}
	
	public function getObjectToInsert()
	{
		$resource = new Resource();
		return $resource;
	}
	
	public function getNameDAOClass()
	{
		return "ResourceDAO";
	}
	
	public function getEntityClassName()
	{
		return "Resource";
	}
	
	public function getTablesToTruncate()
	{
		return array("resource");
	}
}
?>