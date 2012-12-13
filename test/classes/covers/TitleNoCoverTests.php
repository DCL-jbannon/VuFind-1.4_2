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
		$expected = "9c54756a09ab988282e47b80b163b536"; //md5_file
		
		$imageName = $this->baseFolderGenerateImages."TestTitleAuthor.png";
		$title = "a Dummy Title From Testing ";
		$author = "Surname, a Dummy Author Name";
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
		$expected = "d7c98e458869b29edb9d15b081f3f886"; //md5_file
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
					array(CoversType::bookCover, "2326049dedcb10ebed3f2bc2ed04b0df"),
					array(CoversType::audioCover, "f4f995556040ac26989cfe5522c0eb45"),
					array(CoversType::blurayCover, "dd8d9983793f03814093fbf43e7c8e47"),
					array(CoversType::dvdCover, "6d899058deb450618c6db16f174900e1"),
					array(CoversType::emediaCover, "e3eccd993dc22848b78e1956dc453258"),
					array(CoversType::listCover, "af93794001fb2892447f7c6a17befdc9"),
					array(CoversType::magazineCover, "31a3a67b49b910fc3336deadbf2f8040"),
					array(CoversType::musicCover, "be422aba5ec89a5e2f6d4ef87d325149"),
					array(CoversType::otherCover, "0e2f564949d3ad5cd0151841228e35b1")
				);
	}
	
	/**
	* method getCover
	* when notValidCoverType
	* should createImageWithOtherBlankCoverType
	*/
	public function test_getCover_notValidCoverType_createImageWithOtherBlankCoverType()
	{
		$expected = "0e2f564949d3ad5cd0151841228e35b1"; //md5 sum file using the otherCover image
		
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
		$expected = 59;
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
		$expected = "0f7f1ec7a9835203f5a5803818e6ec8f"; //md5_file
		
		$imageName = $this->baseFolderGenerateImages."TestTitle.png";
		$title = "a Dummy Title From Testing";
		$actual = $this->service->getTitleImage($title, $imageName);
		
		$this->assertEquals(3, count($actual));
		$this->assertEquals(280, $actual['width']); //Minimun Width is 280
		$this->assertEquals(115.5, $actual['height']);
		$this->assertTrue(is_resource($actual['resource']));
		
		$actual = md5_file($imageName);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getTitleImage
	 * when longestWordLongerThan12Letters
	 * should executesCorrectly
	 */
	public function test_getTitleImage_longestWordLongerThan12Letters_executesCorrectly()
	{
	
		$title = "1234567890123";
		$actual = $this->service->getTitleImage($title);
		$this->assertEquals(299, $actual['width']); //Minimun Width is 280
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
				  		array("1234567", array("1234567")), //Less Than 12
						array("123456789012", array("123456789012")), // Exactly 12 characters
						array("1234567890123", array("1234567890123")),//1 word longer than 12 characters
						array("123456789 123", array("123456789","123")),//Longer than 12 characters but two words
						array("123456789 123 12315", array("123456789","123 12315")),
						array("123456789 123 12315 1", array("123456789","123 12315 1")),
						array("123456789 123 12315 121", array("123456789","123 12315","121")),
						array("123456789 123 12315 121 1234567890123", array("123456789","123 12315","121","1234567890123")),
						array("Looking for Yesterday", array("Looking for","Yesterday")),
						array("Instrumentals Memories : Elvis Presley", array("Instrumentals","Memories :", "Elvis", "Presley")),
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
				array("1234567", array("1234567")), //Less Than 20
				array("12345678901234567890", array("12345678901234567890")), // Exactly 20 characters
				array("12345678901234567890123", array("12345678901234567890123")),//1 word longer than 20 characters
				array("123456789 12345678901", array("123456789","12345678901")),//Longer than 20 characters but two words
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
		$title = "Juan Bautista Giménez Sendiu";
		$expected = 14;
		$actual = $this->service->maxAuthorLineLength($title);
		$this->assertEquals($expected, $actual);
	}
	
	public function tearDown()
	{
		// http://www.php.net/manual/en/function.unlink.php#109971
		array_map('unlink', glob($this->baseFolderGenerateImages.'\*.png'));
	}
}

?>