<?php

interface IMemcacheWrapper{}

class MemcacheWrapper implements IMemcacheWrapper
{
	private $memcache;
	
	public function __construct()
	{
		global $configArray;
		
		$host = $configArray['Caching']['memcache_host'];
		$port = $configArray['Caching']['memcache_port'];
		$timeout = $configArray['Caching']['memcache_connection_timeout'];
		
		$this->memcache = new Memcache();
		if (!@$this->memcache->pconnect($host, $port, $timeout)) {
			throw new DomainException("Could not connect to Memcache (host = ".$host.", port = ".$port.").");
		}	
	}
	
	public function get($key)
	{
		return $this->memcache->get($key);
	}
	
	public function set($key, $value, $compress, $expire)
	{
		$this->memcache->set($key, $value, $compress, $expire);
	}
	
	public function delete($key)
	{
		$this->memcache->delete($key);
	}

}

?>