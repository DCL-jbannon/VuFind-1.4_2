<?php

require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/OverDriveFormatTranslation.php';

class OverDriveFormatTranslationTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		parent::setUp();		
	}
	
	
	/**
	* method getFormatIdFromString
	* when called
	* should returnCorrectly
	* @dataProvider DP_getFormatIdFromString
	*/
	public function test_getFormatIdFromString_called_returnCorrectly($formatString, $expected)
	{
		$actual = OverDriveFormatTranslation::getFormatIdFromString($formatString);
		$this->assertEquals($expected, $actual);
	}

	public function DP_getFormatIdFromString()
	{
		return array(
				  		array("audiobook-wma", 25),
						array("music-wma", 30),
						array("video-wmv", 35),
						array("ebook-pdf-adobe", 50),
						array("ebook-disney", 302),
						array("ebook-epub-adobe", 410),
						array("ebook-kindle", 420),
						array("ebook-pdf-open", 450),
						array("ebook-epub-open", 810),
						array("audiobook-mp3", 425),
						array("ebook-overdrive", -1)
					);
	}
	
	/**
	* method getMediaTypeFromUrl
	* when called
	* should returnsCorrectly
	* @dataProvider DP_getMediaType
	*/
	public function test_getMediaTypeFromUrl_called_returnsCorrectly($urlSample, $expected)
	{
		$actual = OverDriveFormatTranslation::getMediaTypeFromUrl($urlSample);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getMediaType()
	{
		return array(
				  		array("http://excerpts.contentreserve.com/FormatType-25/0857-1/450829-TreacheryInDeath.wma",OverDriveFormatTranslation::MediaAudio),
						array("http://excerpts.contentreserve.com/FormatType-425/1837-1/355163-11Birthdays.mp3",OverDriveFormatTranslation::MediaAudio),
						array("http://excerpts.contentreserve.com/FormatType-35/1240-1/111926-TheScreamingSkull.wmv", OverDriveFormatTranslation::MediaVideo),
						array("http://excerpts.contentreserve.com/FormatType-35/1240-1/111926-TheScreamingSkull.epub", OverDriveFormatTranslation::MediaEPUB)
					);
	}
		
}
?>