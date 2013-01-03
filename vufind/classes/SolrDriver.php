<?php

require_once dirname(__FILE__).'/Utils/UrlUtils.php';

interface ISolrDriver{}

class SolrDriver implements ISolrDriver
{
	private $solrUrl;
	const jsonFormat='wt=json';
	private $ch;
	private $urlUtils;
	
	public function __construct($solrUrl = NULL, $index = 'testIdclReader', IUrlUtils $urlUtils = NULL)
	{
		if(!$solrUrl) $solrUrl = @SOLRURL;
		$this->solrUrl = $solrUrl.'/'.$index;
		
		if(!$urlUtils) $urlUtils = new UrlUtils();
		$this->urlUtils = $urlUtils;
	}
	
	public function ping()
	{
		$this->initCurl();
		$this->setOpt(CURLOPT_URL, $this->getPingUrl());
		$this->setOpt(CURLOPT_HEADER, 0);
		$this->setReturnResultExec();
		$result = $this->exec();
		return $this->jsonDecode($result);
	}
	
	public function search($query, $start = 0, $rows = 20, $searchParams = NULL)
	{
		$searchParams = $this->urlUtils->encodeParams($searchParams);
		$params = 'q='.$this->urlUtils->encodeParams($query).'&start='.$start.'&rows='.$rows.'&'.self::jsonFormat.'&'.$searchParams;
		$url = $this->solrUrl."/select?".$params;
		$this->initCurl();
		$this->setUrl($url);
		$this->setReturnResultExec();
		return $this->execAndgetResult();
	}
	
	public function add($data)
	{
		$url = $this->solrUrl.'/update/json?commit=true&'.self::jsonFormat;
		
		$this->initCurl();
		$this->setPostJSONData($url, $data);
		return $this->execAndgetResult();
	}
	
	public function delete($id, $deleteAll = false)
	{
		if(!$deleteAll)
		{
			$data['delete']['query']='id:'.$id;
		}
		else
		{
			$data['delete']['query']='*:*';
		}
		$data = json_encode($data);
		$url = $this->solrUrl.'/update/json?commit=true&'.self::jsonFormat;
		$this->initCurl();
		$this->setPostJSONData($url, $data);
		return $this->execAndgetResult();
	}
	
	public function clearIndex()
	{
		return $this->delete('', true);
	}

	
	//Private methods
	private function execAndgetResult()
	{
		$result = $this->exec();
		return $this->jsonDecode($result);
	}
	
	private function setPostJSONData($url, $data)
	{
		$this->setUrl($url);
		$this->setOpt(CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		$this->setReturnResultExec();
		$this->setPost($data);
	}
	
	private function setUrl($url)
	{
		$this->setOpt(CURLOPT_HEADER, 0);
		$this->setOpt(CURLOPT_URL, $url);
	}
	
	private function setPost($data)
	{
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
	}
	
	private function jsonDecode($json_string)
	{
		return json_decode($json_string);
	}
	
	private function setReturnResultExec()
	{
		$this->setOpt(CURLOPT_RETURNTRANSFER, 1);
	}
	private function setOpt($opt, $value)
	{
		curl_setopt($this->ch, $opt, $value);
	}
	
	private function initCurl()
	{
		$this->ch = curl_init();
	}
	
	private function exec()
	{
		return curl_exec($this->ch);
	}
	
	private function close()
	{
		curl_close($this->ch);
	}
	
	private function getPingUrl()
	{
		return $this->solrUrl.'/admin/ping?'.self::jsonFormat;
	}
	
}
?>