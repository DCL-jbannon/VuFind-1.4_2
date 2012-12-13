<?php

class BookcoverURL {
	
	const urlEcontent = 'bookcover.php?id={eContentId}&econtent=true&isn={ISSN}&size={size}&category=EMedia';
	private $baseUrl;
	
	public function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;	
	}
	
	public function getBaseUrl()
	{
		return $this->baseUrl;
	}
	
	public function getBookCoverUrl($size = 'small', $issn, $eContentId, $eContent = false)
	{
		$patterns = array();
		$patterns[0] = '/{eContentId}/';
		$patterns[1] = '/{ISSN}/';
		$patterns[2] = '/{size}/';
		$replacements = array();
		$replacements[0] = $eContentId;
		$replacements[1] = $issn;
		$replacements[2] = $size;
		
		if($eContent)
		{
			$url = preg_replace($patterns, $replacements, self::urlEcontent);
			if(!empty($this->baseUrl))
			{
				$url = $this->baseUrl.'/'.$url;
			}
			return $url;
		}
	}
	
}

?>