<?php
require_once dirname(__FILE__).'/../../../vufind/classes/covers/TitleNoCovers.php';

class TitleNoCoverTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $baseFolderGenerateImages;
	
	public function setUp()
	{
		$this->baseFolderGenerateImages = dirname(__FILE__)."/";
		$this->service = new TitleNoCovers();
		parent::setUp();
	}
	
	
	/**
	* method getTitleAuthorImage
	* when called
	* should executesCorrectly
	*/
	public function test_getTitleAuthorImage_called_executesCorrectly()
	{
		$expected = "b4b397e81e1ebe92f18c419a61646e55"; //md5_file
		
		$imageName = $this->baseFolderGenerateImages."TestTitleAuthor.png";
		$title = "a Dummy Title From Testing ";
		$author = "Surname, a Dummy123 Author Name";
		$this->service->getTitleAuthorImage($title, $author, $imageName);
		
		$actual = md5_file($imageName);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getAuthorImage
	* when called
	* should executesCorrectly
	*/
	public function test_getAuthorImage_called_executesCorrectly()
	{
		$expected = "726713a83d85b70d2bc1e351b6181233"; //md5_file
		$imageName = $this->baseFolderGenerateImages."TestAuthor.png";
		
		$this->service->getAuthorImage("Surname, a Dummy Author Name ", $imageName);
		
		$actual = md5_file($imageName);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getAuthorImage
	 * when calledEmptyString
	 * should executesCorrectly
	 */
	public function test_getAuthorImage_calledEmptyString_executesCorrectly()
	{
		$this->service->getAuthorImage("");
	}
	
	/**
	* method getCover
	* when called
	* should executesCorrectly
	* @dataProvider DP_getCover
	*/
	public function test_getCover_called_executesCorrectly($type, $expected)
	{
		$imageName = $this->baseFolderGenerateImages."TestCoverTitleAuthor_".$type.".png";
		$title = "a Dummy Title From Testing a Dummy Title From Testing a Dummy Title From Testing";
		$author = "Surname, a Dummy Author Name";
		
		$this->service->getCover($title, $author, $type, $imageName);
		
		$actual = md5_file($imageName);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getCover()
	{
		return array(
					array(CoversType::bookCover, "a4c68e413a83e76047e853454425afd1"),
					array(CoversType::audioCover, "708d899f08bec85e864a780a2c44d5e3"),
					array(CoversType::blurayCover, "b395dca9907ddecb9c139c9d9f20a778"),
					array(CoversType::dvdCover, "d1a17c8876fdcc8d76922dcbd2efe497"),
					array(CoversType::emediaCover, "938e750389517e556ae96f13c88f36fa"),
					array(CoversType::listCover, "f7c4bb38851d7296be3939c3d8f7da01"),
					array(CoversType::magazineCover, "58f24b9d63b053a8de46117ae4114a3a"),
					array(CoversType::musicCover, "fe9c46a63a45ba87498bd1c260bb1698"),
					array(CoversType::otherCover, "86c61fd2983fd67082e3f557333c0433")
				);
	}
	
	/**
	* method getCover
	* when notValidCoverType
	* should createImageWithOtherBlankCoverType
	*/
	public function test_getCover_notValidCoverType_createImageWithOtherBlankCoverType()
	{
		$expected = "86c61fd2983fd67082e3f557333c0433"; //md5 sum file using the otherCover image
		
		$imageName = $this->baseFolderGenerateImages."TestCoverTitleAuthor_NotValidCover.png";
		$title = "a Dummy Title From Testing a Dummy Title From Testing a Dummy Title From Testing";
		$author = "Surname, a Dummy Author Name";
		$type = "aNonValidCoverType";
		$this->service->getCover($title, $author, $type, $imageName);
		
		$actual = md5_file($imageName);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getXValueTitleLine
	* when called
	* should returnsCoorectly
	*/
	public function test_getXValueTitleLine_called_returnsCoorectly()
	{
		$expected = 66;
		$actual = $this->service->getXValueTitleLine("1234567", 280);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getXValueAuthorLine
	 * when called
	 * should returnsCoorectly
	 */
	public function test_getXValueAuthorLine_called_returnsCoorectly()
	{
		$expected = 91;
		$actual = $this->service->getXValueAuthorLine("1234567", 280);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getTitleImage
	* when called
	* should executesCorrectly
	*/
	public function test_getTitleImage_called_executesCorrectly()
	{
		$expected = "281175b32df9576940bf838ab9a8dce7"; //md5_file
		
		$imageName = $this->baseFolderGenerateImages."TestTitle.png";
		$title = "a Dummy Title From Testing";
		$actual = $this->service->getTitleImage($title, $imageName);
		
		$this->assertEquals(3, count($actual));
		$this->assertEquals(280, $actual['width']); //Minimun Width is 280
		$this->assertEquals(90, $actual['height']);
		$this->assertTrue(is_resource($actual['resource']));
		
		$actual = md5_file($imageName);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getTitleImage
	 * when longestWordLongerThan13Letters
	 * should executesCorrectly
	 */
	public function test_getTitleImage_longestWordLongerThan13Letters_executesCorrectly()
	{
	
		$title = "12345678901234";
		$actual = $this->service->getTitleImage($title);
		$this->assertEquals(294, $actual['width']); //Minimun Width is 280
	}
	
	/**
	 * method getTitleImage
	 * when longestWordShorterThan12Characters
	 * should executesCorrectly
	 */
	public function test_getTitleImage_longestWordShorterThan12Characters_executesCorrectly()
	{
	
		$title = "12345678901";
		$actual = $this->service->getTitleImage($title);
		$this->assertEquals(280, $actual['width']); //Minimun Width is 280
	}
	

	/**
	* method getTitleParts
	* when called
	* should executesCorrectly
	* @dataProvider DP_getTitleParts
	*/
	public function test_getTitleParts_called_executesCorrectly($title, $expected)
	{
		$actual = $this->service->getTitleParts($title);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getTitleParts()
	{
		return array(
				  		array("1234567", array("1234567")), //Less Than 13
						array("1234567890123", array("1234567890123")), // Exactly 13 characters
						array("12345678901234", array("12345678901234")),//1 word longer than 13 characters
						array("123456789 1234", array("123456789","1234")),//Longer than 13 characters but two words
						array("123456789 1234 12315", array("123456789","1234 12315")),
						array("123456789 123 12315 1", array("123456789 123", "12315 1")),
						array("123456789 123 12315 121", array("123456789 123", "12315 121")),
						array("123456789 123 12315 121 1234567890123", array("123456789 123", "12315 121", "1234567890123")),
						array("Looking for Yesterday", array("Looking for","Yesterday")),
						array("Instrumentals Memories : Elvis Presley", array("Instrumentals","Memories :", "Elvis Presley")),
					);
	}
	
	/**
	 * method getAuthorParts
	 * when called
	 * should executesCorrectly
	 * @dataProvider DP_getAuthorParts
	 */
	public function test_getAuthorParts_called_executesCorrectly($title, $expected)
	{
		$actual = $this->service->getAuthorParts($title);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getAuthorParts()
	{
		return array(
				array("1234567", array("1234567")), //Less Than 16
				array("12345678901234567890", array("12345678901234567890")), // Exactly 16 characters
				array("12345678901234567890123", array("12345678901234567890123")),//1 word longer than 16 characters
				array("123456789 12345678901", array("123456789","12345678901")),//Longer than 16 characters but two words
				array("123456789 123 12315", array("123456789 123 12315")),
				array("123456789 123 12315 1", array("123456789 123 12315","1")),
				array("Leist, Laura", array("Leist, Laura")),
				array("Rockabye Baby! Leist, Laura", array("Rockabye Baby!","Leist, Laura")),
				array("123456789 123  12315 1", array("123456789 123 12315","1")),
		);
	}
	
	/**
	* method maxTitleLineLength
	* when called
	* should returnCorrectly
	*/
	public function test_maxTitleLineLength_called_returnCorrectly()
	{
		$title = "Looking for Yesterday";
		$expected = 11;
		$actual = $this->service->maxTitleLineLength($title);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method maxAuthorLineLength
	 * when called
	 * should returnCorrectly
	 */
	public function test_maxAuthorLineLength_called_returnCorrectly()
	{
		$title = "Juan Bautista Gimenez Sendiu";
		$expected = 14;
		$actual = $this->service->maxAuthorLineLength($title);
		$this->assertEquals($expected, $actual);
	}
	
	public function tearDown()
	{
		// http://www.php.net/manual/en/function.unlink.php#109971
		array_map('unlink', glob($this->baseFolderGenerateImages.'*.png'));
	}
}

?>