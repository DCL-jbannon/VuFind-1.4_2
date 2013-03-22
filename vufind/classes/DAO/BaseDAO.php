<?php
abstract class BaseDAO
{
	abstract public function getEntityName();
	
	public function insert(DB_DataObject &$object)
	{
		$object->insert();
	}
	
	public function update(DB_DataObject $object)
	{
		$object->update();
	}

	public static function noResult()
	{
		return array();
	}
	
	/**
	 * Return an Entity
	 * @param integer $id
	 */
	public function getById($id)
	{
		$entityName = $this->getEntityName();
		$object = new $entityName();
		$object->id = $id;
		$result = $object->find(true);
		if($result)
		{
			return $object;
		}
		return self::noResult();
	}
}
?>