<?php
require_once dirname(__FILE__).'/CoversType.php';
/**
 * Information for Title
 * 		Widht Every Single Character : 23px
 * 		Max Characters in one line: 12
 * Information for Author
 * 		Widht Every Single Character : 14px
 * 		Max Characters in one line: 20 
 * 
 * 			IMAGE
 *              Width: 280px
 *               Height: 400px
 *         --------------------
 * 		   |                  |
 * 		   |      ICON        | 
 * 		   |                  |
 * 		   |******************|
 * 		   |                  | 
 * 		   |    PRINTABLE     |   
 * 		   |                  |  Printable Area in pixel: 
 * 		   |                  |
 * 		   |      COVER       |   
 * 		   |                  |   
 *         --------------------
 *         
 *         
 * @author jgimenez@dclibraries.org
 */




class TitleNoCovers {
	
	private $titleConst  = array(
								"maxCharPerLine"=>12,
								"fontSize"=>29,
								"pixelsCharacter"=>23,
								"YPositionFirtsLineOnCover" => 40, //May be more than one line for the title.
								"incrementYPosition" => 33
							);
	
	private $authorConst  = array(
								"maxCharPerLine"=>20, //Manually tested
								"fontSize"=>18, 
								"pixelsCharacter"=>14, // 280/20
								"YPositionFirtsLineAuthorImage" => 35,
								"incrementYPosition" => 30
							);
	
	const angle = 0; //The text angle is always Zero 
	const imageWidth = 280;  //Pixels
	const imageHeight = 400; // Pixels
	const imagePrintableAreaHeight = 290; //Area printable in Pixels
	private $fontText; 
	private $colorText = array("red"=>255, "green"=>255, "blue"=>255);
	
	public function __construct()
	{
		$this->fontText = dirname(__FILE__).'/fonts/COURBD.TTF'; //same lenght per character
	}
	
	
	public function getCover($title, $author, $type, /*Test*/ $imageName = NULL, /*Test*/ $blankCoverPNGFilename = NULL)
	{
		if(!CoversType::isValid($type))
		{
			$type = CoversType::otherCover; 
		}
		
		$titleAuthorData = $this->getTitleAuthorImage($title, $author);
		
		$blanckCover = imagecreatefrompng(dirname(__FILE__).'/blankCovers/'.$type.'.png');
		if($blankCoverPNGFilename !== NULL)
		{
			$blanckCover = imagecreatefrompng($blankCoverPNGFilename);
		}

		$paddingLeft = 0;
		if($titleAuthorData['height'] > self::imagePrintableAreaHeight)
		{
			$titleAuthorData = $this->scaleImageByHeigth($titleAuthorData['resource'], $titleAuthorData['width'], $titleAuthorData['height']);
			$paddingLeft =  ((self::imageWidth - $titleAuthorData['width']) / 2);
		}
		
		$paddingTop = (self::imageHeight - self::imagePrintableAreaHeight);
		$paddingTop += intval((self::imagePrintableAreaHeight - $titleAuthorData['height'])/2);
		
		imagecopymerge($blanckCover, $titleAuthorData['resource'], $paddingLeft, $paddingTop, 0, 0, $titleAuthorData['width'], $titleAuthorData['height'], 100);
		
		
		/**TEST PURPOUSE*/
		if($imageName !== NULL)
		{
			imagepng($blanckCover, $imageName, 0);
		}
		/**TEST PURPOUSE*/
		
		return $this->prepareReturnImageMetadata($blanckCover, self::imageWidth, self::imageHeight);
	}
	
	
	/**
	 * Scale a Image to a proper width
	 * @param resource $imResource
	 * @param integer $widht
	 * @param integer $height
	 */
	private function scaleImageByHeigth($imResource, $width, $height)
	{
		$newHeight = self::imagePrintableAreaHeight;
		$percentHeightShrink = (self::imagePrintableAreaHeight * 100) / $height;
		$newWidth = intval( ($percentHeightShrink * $width) / 100);
		return $this->scaleImage($newWidth, $newHeight, $width, $height, $imResource);
	}
	
	/**
	 * Scale a Image to a proper width
	 * @param resource $imResource
	 * @param integer $widht
	 * @param integer $height
	 */
	private function scaleImageByWidth($imResource, $width, $height)
	{
		$newwidth = self::imageWidth;
		$percentWidthShrink = (self::imageWidth * 100) / $width;
		$newHeight = intval( ($percentWidthShrink * $height) / 100);
		return $this->scaleImage($newwidth, $newHeight, $width, $height, $imResource);
	}
	
	private function scaleImage($newwidth, $newHeight, $width, $height, $imResource)
	{
		$thumb = imagecreatetruecolor($newwidth, $newHeight);
		$black = imagecolorallocate($thumb, 0, 0, 0);
		imagecolortransparent($thumb, $black); //Make the image transparent. Black is the background color by default.
		imagecopyresized($thumb, $imResource, 0, 0, 0, 0, $newwidth, $newHeight, $width, $height);
		return $this->prepareReturnImageMetadata($thumb, $newwidth, $newHeight);
	}
	
	
	
	public function getXValueTitleLine($titleLine, $width)
	{
		return $this->getXValueLine($titleLine, $width, $this->titleConst);
	}
	
	public function getXValueAuthorLine($authorLine, $width)
	{
		return $this->getXValueLine($authorLine, $width, $this->authorConst);
	}
	
	private function getXValueLine($line, $width, $configuration)
	{
		$x = 0;
		$numberCharacters = strlen($line);
		$pixelsForLine = $numberCharacters * $configuration['pixelsCharacter'];
		$freePixels = $width - $pixelsForLine;
		$freeSidesPixels = intval($freePixels/2);
		$x = $freeSidesPixels;
		return $x;
	}
	
	
	private function getTextImage($lines, $configuration, $methodXValue,  /*Test*/ $imageName = NULL)
	{
		$maxLineLength = $this->maxLineLength($lines);
		
		$width = $maxLineLength * $configuration['pixelsCharacter'];
		
		if ($width < self::imageWidth)
		{
			$width = self::imageWidth;
		}
		
		$height = ( count($lines) + 0.5) * $configuration['incrementYPosition'];
		$fontSize = $configuration['fontSize'];
		
		$im = imagecreatetruecolor($width, $height);
		$colorText = imagecolorallocate($im, $this->colorText['red'], $this->colorText['green'], $this->colorText['blue']); //#444444
		
		$black = imagecolorallocate($im, 0, 0, 0);
		imagecolortransparent($im, $black); //Make the image transparent. Black is the background color by default.
		
		$x = 0;
		foreach ($lines as $key=>$val)
		{
			$x = $this->$methodXValue($lines[$key], $width);
			$y = $configuration['incrementYPosition'] * ($key+1);
			imagettftext($im, $fontSize, self::angle, $x, $y, -$colorText, $this->fontText, $lines[$key]);
		}
		
		/**TEST PURPOUSE*/
			if($imageName !== NULL)
			{
				imagepng($im, $imageName, 0);
			}
		/**TEST PURPOUSE*/
		
		return $this->prepareReturnImageMetadata($im, $width, $height);
	}
	
	public function getAuthorImage($author, /*Test*/ $imageName = NULL)
	{
		$partsAuthor = $this->getAuthorParts($author);
		return $this->getTextImage($partsAuthor, $this->authorConst, "getXValueAuthorLine", $imageName);
	}
	
	
	public function getTitleImage($title, /*Test*/ $imageName = NULL)
	{
		$partsTitle = $this->getTitleParts($title);
		return $this->getTextImage($partsTitle, $this->titleConst, "getXValueTitleLine", $imageName);
	}
	
	public function getTitleAuthorImage($title, $author, /*Test*/ $imageName = NULL)
	{
		$imTitle = $this->getTitleImage($title);
		$imAuthor = $this->getAuthorImage($author);
		
		if($imTitle['width'] > self::imageWidth)
		{
			$imTitle = $this->scaleImageByWidth($imTitle['resource'], $imTitle['width'], $imTitle['height']);
		}
		
		if($imAuthor['width'] > self::imageWidth)
		{
			$imAuthor = $this->scaleImageByWidth($imAuthor['resource'], $imAuthor['width'], $imAuthor['height']);
		}
		//All the images are now with the width
		//Let's go to glue them
		
		$newHeight = $imTitle['height'] + $imAuthor['height'];
		
		$titleAuthorImage = imagecreatetruecolor(self::imageWidth, $newHeight);
		$black = imagecolorallocate($titleAuthorImage, 0, 0, 0);
		imagecolortransparent($titleAuthorImage, $black); //Make the image transparent. Black is the background color by default.
		
		imagecopymerge($titleAuthorImage, $imTitle['resource'], 0, 0, 0, 0, $imTitle['width'], $imTitle['height'], 100);
		imagecopymerge($titleAuthorImage, $imAuthor['resource'], 0, $imTitle['height'], 0, 0, $imAuthor['width'], $imAuthor['height'], 100);
		
		/**TEST PURPOUSE*/
		if($imageName !== NULL)
		{
			imagepng($titleAuthorImage, $imageName, 0);
		}
		/**TEST PURPOUSE*/
		return $this->prepareReturnImageMetadata($titleAuthorImage, self::imageWidth, $newHeight);
	}
	
	
	public function maxAuthorLineLength($author)
	{
		$partsAuthor = $this->getAuthorParts($author);
		return $this->maxLineLength($partsAuthor);
	}
	
	public function maxTitleLineLength($title)
	{
		$partsTitle = $this->getTitleParts($title);
		return $this->maxLineLength($partsTitle);
	}
	
	private function maxLineLength($partsString)
	{
		
		$maxLength = 0;
		foreach($partsString as $line)
		{
			if (strlen($line) > $maxLength)
			{
				$maxLength = strlen($line);
			}
		}
		return $maxLength;
	}
	
	public function getTitleParts($title)
	{
		return $this->getStringParts($title, $this->titleConst['maxCharPerLine']);
	}
	
	public function getAuthorParts($author)
	{
		return $this->getStringParts($author, $this->authorConst['maxCharPerLine']);
	}
	
	
	private function prepareReturnImageMetadata($resource, $width, $height)
	{
		return array("resource"=>$resource, "width"=>$width, "height"=>$height);
	}
	
	private function noEmptyLines($lines)
	{
		$processedLines = array();
		foreach ($lines as $key=>$val)
		{
			if (trim($lines[$key] != ''))
			{
				$processedLines[]=$lines[$key];
			}
		}
		return $processedLines;
	}
	
	private function getStringParts($text, $maxCharsPerLine)
	{
		$parts = explode(" ", $text);
		$wordsNumbers = count($parts);
		if ($wordsNumbers == 1)
		{
			return array($text);
		}
		
		if (strlen($text) <= $maxCharsPerLine)
		{
			return array($text);
		}
		
		$parts = $this->noEmptyLines($parts);
		
		$preWord = NULL;
		$titleParts = array();
		$i = 0;
		foreach ($parts as $word)
		{
			if ($preWord === NULL)
			{
				if (strlen($word)>$maxCharsPerLine)
				{
					$titleParts[$i] = $word;
					$i++;
				}
				else
				{
					$preWord = $word;
				}
			}
			else
			{
				if (strlen($preWord." ".$word) > $maxCharsPerLine)
				{
					$titleParts[$i] = $preWord;
					$i++;
					if(strlen($word)>12)
					{
						$titleParts[$i] = $word;
						$i++;
						$preWord = NULL;
					}
					else
					{
						$preWord = $word;
					}
				}
				else
				{
					$preWord = $preWord." ".$word;
				}
			}
		}
		if($preWord !== NULL)
		{
			$titleParts[$i] = $preWord;
		}
		
		return $titleParts;
		
	}
	
}

?>