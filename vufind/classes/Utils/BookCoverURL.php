<?php

interface IBookcoverURL{}

class BookcoverURL implements IBookcoverURL{
	
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
	
	public function getBookCoverUrl($size = 'small', $issn, $eContentRecordId, $eContent = false)
	{
		global $configArray;
		
		$patterns = array();
		$patterns[0] = '/{eContentId}/';
		$patterns[1] = '/{ISSN}/';
		$patterns[2] = '/{size}/';
		$replacements = array();
		$replacements[0] = $eContentRecordId;
		$replacements[1] = $issn;
		$replacements[2] = $size;
		
		if($eContent)
		{
			$url = preg_replace($patterns, $replacements, self::urlEcontent);
			
			if(empty($this->baseUrl))
			{
				$this->setBaseUrl($configArray['Site']['url']);
			}			
			return $this->baseUrl.'/'.$url;
		}
	}
	
}

?>