<?php
require_once dirname(__FILE__).'/MemcacheWrapper.php';

interface IMemcacheServices{}

class MemcacheServices implements IMemcacheServices
{
	const defTimeout = 300;
	const compress = 0;
	private $memcacheWrapper;
	
	public function __construct(IMemcacheWrapper $memcacheWrapper = NULL)
	{
		if(!$memcacheWrapper) $memcacheWrapper = new MemcacheWrapper();
		$this->memcacheWrapper = $memcacheWrapper;
	}
	
	public function set($key, $value, $timeout)
	{
		$this->memcacheWrapper->set($key, serialize($value), self::compress, $timeout);
		return true;
	}
	
	public function call($classInstantiated, $methodToCall, $parameters, $key = NULL, $timeout = NULL)
	{
		if(!$key)
		{
			$key = get_class($classInstantiated)."_".$methodToCall;
		}
		
		if(!$timeout) $timeout = self::defTimeout;
		
		$result = $this->memcacheWrapper->get($key);
		if($result !== FALSE)
		{
			$result = unserialize($result);
			if(is_array($result))
			{
				if(isset($result['__SimpleXMLElement']))
				{
					return new SimpleXMLElement($result['xml']);
				}
			}
			return $result;
		}

		$resultToSerialize = $result = call_user_func_array(array($classInstantiated, $methodToCall), $parameters);
		if(is_object($result))
		{
			if(is_a($result, "SimpleXMLElement"))
			{
				$resultToSerialize = array("__SimpleXMLElement"=>true, "xml"=>$result->asXML());
			}
		}
		
		$this->memcacheWrapper->set($key, serialize($resultToSerialize), self::compress, $timeout);
		return $result;
	}
	
}
?>