<?php
require_once 'File/MARC.php';
require_once dirname(__FILE__).'/FileMARCRecord.php';

interface IFileMarc{}

class FileMarc extends File_Marc implements IFileMarc
{
	
	public function __construct($source, $type = File_Marc::SOURCE_FILE)
	{
		parent::__construct($source, $type);
	}
	
	private function __clone(){}
	
	
	/**
	 * @return boolean|FileMARCRecord|NULL
	 */
	public function next()
	{
		$result = parent::next();
		if ($result === false)
		{
			return false;
		}
		
		try {
			$rc = new ReflectionClass($result);
		}
		catch(Exception $e)
		{
			echo "Exception: ".$e->getMessage().' >> Class tried: '.get_class($result);
			return false;
		}
		$props = $rc->getProperties();
		if (!empty($props))
		{
			$args = array();
			foreach ($props as $prop)
			{
				$property = $rc->getProperty($prop->getName());
				$property->setAccessible(true);
				$args[$prop->getName()] = $property->getValue($result);
			}
			return FileMARCRecord::getFileMarcRecord($args);
		}
		else
		{
			return NULL;
		}
	}
	
}

?>