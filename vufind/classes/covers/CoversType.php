<?php

class CoversType
{
	const bookCover = "book";
	const audioCover = "audio";
	const blurayCover = "bluray";
	const dvdCover = "dvd";
	const emediaCover = "emedia";
	const listCover = "list";
	const magazineCover= "magazine";
	const musicCover= "music"; //For CD
	const otherCover= "other";
	
	static public function isValid($coverType)
	{
		switch ($coverType)
		{
			case self::bookCover:
			case self::audioCover:
			case self::blurayCover:
			case self::dvdCover:
			case self::emediaCover:
			case self::listCover:
			case self::magazineCover:
			case self::musicCover:
			case self::otherCover:
				return true;
				break;
			default:
				return false;
				break;
		}
	}
	
	static public function getCoverTypeFromFormat($format)
	{
		switch ($format)
		{
			case "Books":
				return self::bookCover;
				break;
			case "Audio Book":
				return self::audioCover;
				break;
			case "Blu-Ray":
				return self::blurayCover;
				break;
			case "DVD":
				return self::dvdCover;
				break;
			case "Magazine":
				return self::magazineCover;
				break;
			case "Music":
				return self::musicCover;
				break;
			case "Other":
			default:
				return self::otherCover;
				break;
		}
	}
	
}
?>