<?php
require_once dirname(__FILE__).'/DAOTests.php';
require_once dirname(__FILE__).'/../../vufind/classes/DAO/EcontentRecordDAO.php';

class EcontentRecordDAOTests extends DAOTests
{	

	public function getObjectToInsert()
	{
		$object = $this->getObject();
		$object->title = "aDummyTitle";
		return $object;
	}
	
	public function getNameDAOClass()
	{
		return "EcontentRecordDAO";
	}
	
	public function getEntityClassName()
	{
		return "EContentRecord";
	}
	
	public function getTablesToTruncate()
	{
		return array("econtent_record");
	}
	
	private function getObject()
	{
		$er = new EContentRecord();
		$er->insertToSolr = false;
		return $er;
	}
}
?>