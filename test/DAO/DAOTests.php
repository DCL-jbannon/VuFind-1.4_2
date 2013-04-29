<?php

abstract class DAOTests extends PHPUnit_Framework_TestCase
{
	const vufindTestDatabase = "testVufindDB";
	protected $service;
	private static $createdDB = NULL;
	private static $mysqli = NULL;
	
	protected $daoClassName;
	protected $entityClassName;
	
	abstract public function getTablesToTruncate();
	abstract public function getObjectToInsert();
	abstract public function getNameDAOClass();
	abstract public function getEntityClassName();
	
	public function setUp()
	{
		if(self::$mysqli === NULL)
		{
			$this->setUpConnection();
			self::$mysqli = new mysqli('localhost', TEST_DBUSER, TEST_DBUSERPASS);
			if (self::$mysqli->query("CREATE DATABASE IF NOT EXISTS ".self::vufindTestDatabase) !== TRUE)
			{
				die(self::$mysqli->error);
			}
			
			self::$mysqli = new mysqli('localhost', TEST_DBUSER, TEST_DBUSERPASS, self::vufindTestDatabase);
			if (self::$mysqli->multi_query(file_get_contents(dirname(__FILE__).'/../sql/dclvufind.sql')) !== TRUE)
			{
				die(self::$mysqli->error);
			}
			
			self::$mysqli->close();
			self::$mysqli = new mysqli('localhost', TEST_DBUSER, TEST_DBUSERPASS, self::vufindTestDatabase);
		}
		
		$this->daoClassName = $this->getNameDAOClass();
		$this->entityClassName = $this->getEntityClassName();
		
		$this->service = new $this->daoClassName();
		
		parent::setUp();		
	}
	

	/**
	* method insert 
	* when called
	* should executesCorrectly
	*/
	public function test_insert_called_executesCorrectly()
	{   
		$object = $this->getObjectToInsert();
		$this->service->insert($object);
		
		$object = new $this->entityClassName();
		$object->id = 1;
		
		$result = $object->find(true);
		$this->assertEquals(1, $result);  //1 as a True. 
	}
	
	/**
	* method getById 
	* when called
	* should executesCorrectly
	*/
	public function test_getById_called_executesCorrectly()
	{
		
		$object = $this->getObjectToInsert();
		$this->service->insert($object);
		
		$dao = $this->getNameDAOClass();
		$object2 = new $dao();
		
		$actual = $object2->getById(1);
		$this->assertEquals(1, $actual->id);
	}
	
		
	
	public function tearDown()
	{
		$tablesToTruncate = $this->getTablesToTruncate();
		if(!empty($tablesToTruncate))
		{
			foreach($tablesToTruncate as $table)
			{
				$sql = "TRUNCATE TABLE `".$table."`";
				if (self::$mysqli->query($sql) !== TRUE)
				{
					die(self::$mysqli->error);
				}
			}	
		}
		parent::tearDown();
	}
	
	
	private function setUpConnection()
	{
		global $configArray;
		define('DB_DATAOBJECT_NO_OVERLOAD', 0);
		$options = &PEAR::getStaticProperty('DB_DataObject','options');
		
		$options = $configArray['Database'];
		foreach($options as $key=>$val)
		{
			switch($val)
			{
				case "vufind":
				case "econtent":
					$options[$key] = 'vufindtestdb';
					break;
					
			}
		}
	}
}
?>