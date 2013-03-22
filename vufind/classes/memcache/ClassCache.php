<?php
require_once dirname(__FILE__).'/MemcacheServices.php';

class ClassCache
{
	private $classInstantiated;
	private $memcacheServices;
	private $key = NULL;
	private $timeout = NULL;
	private $uniqueKey = NULL;
	const defaultTimeout = 300;
	
	public function __construct($classInstantiated, $timeout = NULL, IMemcacheServices $memcacheServices = NULL)
	{
		$this->classInstantiated = $classInstantiated;
		if($timeout === NULL) $timeout = self::defaultTimeout;
		$this->timeout = $timeout;
		
		if(!$memcacheServices) $memcacheServices = new MemcacheServices();
		$this->memcacheServices = $memcacheServices;
	}
	
	public function __call($name, $arguments)
	{
		if (!method_exists($this->classInstantiated, $name))
		{
			throw new DomainException("The class ".get_class($this->classInstantiated)." has no method called ".$name);
		}
		
		$key = $this->getKey($name);
		return $this->memcacheServices->call($this->classInstantiated, $name, $arguments, $key , $this->timeout);
	}
	
	public function setKey($key)
	{
		$this->key = $key;
	}
	
	public function setTimeout($timeout)
	{
		$this->timeout = $timeout;
	}
	
	public function getKey($name)
	{
		if($this->uniqueKey !== NULL)
		{
			return get_class($this->classInstantiated)."_".$this->uniqueKey."_".$name;
		}
		return $this->key;
	}
	
	public function resetDefaults()
	{
		$this->key = NULL;
		$this->timeout = NULL;
		$this->uniqueKey = NULL;
	}
	
	public function setUniqueKey($uniqueKey)
	{
		$this->uniqueKey = $uniqueKey;
	}
	
	/*
	 * TEST PURPOUSE
	 */
	
	public function getClassNameInUse()
	{
		return get_class($this->classInstantiated);
	}
}

?>