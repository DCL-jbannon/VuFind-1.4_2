<?php
interface IDB_DataObjectUtils{}

abstract class BaseDB_DataObjectUtils implements IDB_DataObjectUtils
{
	
	abstract public function getClasDBName();
	
	protected $dbObject;
	
	public function __construct(IDB_DataObjectUtils $dbObject = NULL)
	{
		$className = $this->getClasDBName();
		if(!$dbObject) $dbObject = new $className();
		$this->dbObject = $dbObject;
	}
	
	public function getObjectById($id)
	{
		$object = $this->getCleanObject();
		$object->id = $id;
		if(!$object->find(true))
		{
			return false;
		}
		return $object;
	}
	
	protected function getCleanObject()
	{
		return clone $this->dbObject;
	}
	
}

?>