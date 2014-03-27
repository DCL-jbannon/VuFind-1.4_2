<?php
require_once dirname(__FILE__).'/../Utils/EContentFormatType.php';

class FreeEcontentRecordServices {
	
	public function getFormatType(IEContentRecord $eContentRecord)
	{
		if($eContentRecord->sourceUrl == '')
		{
			return EContentFormatType::unknown;
		}elseif ( (preg_match('/ebooks/i', $eContentRecord->genre) > 0) 
				|| 
			 (preg_match('/electronic (bk|book)/i', $eContentRecord->isbn) > 0))
		{
			return EContentFormatType::eBook;
		}
		
		if ( (preg_match('/eaudio/i', $eContentRecord->genre) > 0 ))
		{
			return EContentFormatType::eAudio;
		}
		
		if(preg_match('/evideo/i', $eContentRecord->genre) > 0 )
		{
			return EContentFormatType::eVideo;
		}
		
		if (preg_match('/Freegal/i', $eContentRecord->source) > 0)
		{
			return EContentFormatType::eMusic;
		}
			
		return EContentFormatType::unknown;
	}
	
}

?>