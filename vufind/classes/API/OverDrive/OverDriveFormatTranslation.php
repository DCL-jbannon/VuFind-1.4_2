<?php

require_once dirname(__FILE__).'/OverDriveAPIException.php';

class OverDriveFormatTranslation {
	
	const MediaAudio = "Audio";
	const MediaVideo = "Video";
	const MediaEPUB = "EPUB";

	static public function getMediaTypeFromUrl($url)
	{
		if (preg_match("/\.wma$/",$url) > 0 )
		{
			return self::MediaAudio;
		}
		if (preg_match("/\.mp3$/",$url) > 0 )
		{
			return self::MediaAudio;
		}
		if (preg_match("/\.wmv$/",$url) > 0 )
		{
			return self::MediaVideo;
		}
		if (preg_match("/\.epub$/",$url) > 0 )
		{
			return self::MediaEPUB;
		}
	}
	
	static public function getFormatIdFromString($formatString)
	{
		switch ($formatString)
		{
			case "audiobook-wma":
				return 25;
				break;	
			case "video-wmv":
				return 35;
				break;
			case "music-wma":
				return 30;
				break;
			case "ebook-pdf-adobe":
				return 50;
				break;
			case "ebook-disney":
				return 302;
				break;
			case "ebook-epub-adobe":
				return 410;
				break;
			case "ebook-kindle":
				return 420;
				break;
			case "ebook-epub-open":
				return 810;
				break;
			case "ebook-pdf-open":
				return 450;
				break;
			case "audiobook-mp3":
				return 425;
				break;
			case "ebook-overdrive":
				return -1;
				break;
			default:
				throw new OverDriveAPIException("OverDriveFormatTranslation::getFormatIdFromString No format found: ".$formatString);
				break;
										
		}
	}
	
}

?>