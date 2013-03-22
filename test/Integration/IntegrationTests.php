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
		$expected = "49d113d9832b1f797454aba13534685e";
		$actual = file_get_contents("http://dcl.localhost/bookcover.php?id=1087728&isn=9780307596901&size=small&upc=&category=Books&t=032013&reload");
		$this->assertEquals($expected, md5(base64_encode($actual)));
	}
	
	/**
	 * method checkBookCover
	 * when overDriveCover
	 * should returnCorrectImage
	 */
	public function test_checkBookCover_overDriveCover_returnCorrectImage()
	{
		$expected = "5c33df4fbea61529679d2980c55764d6";
		$actual = file_get_contents("http://dcl.localhost/bookcover.php?id=9003&econtent=true&isn=9780307736772&size=small&upc=&category=EMedia&format=&t=032013&reload");
		$this->assertEquals($expected, md5(base64_encode($actual)));
	}
	
		
	
}