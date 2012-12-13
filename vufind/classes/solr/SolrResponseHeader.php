<?php

interface ISolrResponseHeader{}

class SolrResponseHeader implements ISolrResponseHeader
{
	
	private $status;
	private $QTime;
	private $params;
	
	public function set($responseHeader)
	{
		$this->setStatus($responseHeader->status);
		$this->setQTime($responseHeader->QTime);
		$this->setParams($responseHeader->params);
	}
	
	public function getQueryString()
	{
		return $this->params['q'];
	}
	
	private function setParams($params)
	{
		$this->params = array();
		$properties = get_object_vars($params);
		if(!empty($properties))
		{
			$args = array();
			foreach($properties as $property => $value)
			{
  				$args[$property] = $value;
			}
			$this->params = $args;
		}
	}
	
	public function getStart()
	{
		return $this->params['start'];
	}
	
	public function getRows()
	{
		return $this->params['rows'];
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	private function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getQTime()
	{
		return $this->QTime;
	}
	
	private function setQTime($QTime)
	{
		$this->QTime = $QTime;
	}
	
}

?>