<?php

class IntegrationTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
		
	/**
	* method checkBookCover 
	* when called
	* should returnCorrectImage
	*/
	public function test_checkBookCover_called_returnCorrectImage()
	{
		$actual = file_get_contents("http://".TEST_SERVERNAME."/bookcover.php?id=1087728&isn=9780307596901&size=small&upc=&category=Books&t=032013&reload");
		$this->assertTrue(is_resource(imagecreatefromstring($actual)));	
	}
	
	/**
	 * method checkBookCover
	 * when overDriveCover
	 * should returnCorrectImage
	 */
	public function test_checkBookCover_overDriveCover_returnCorrectImage()
	{
		$actual = file_get_contents("http://".TEST_SERVERNAME."/bookcover.php?id=9003&econtent=true&isn=9780307736772&size=small&upc=&category=EMedia&format=&t=032013&reload");	
		$this->assertTrue(is_resource(imagecreatefromstring($actual)));
	}
	
		
	
}