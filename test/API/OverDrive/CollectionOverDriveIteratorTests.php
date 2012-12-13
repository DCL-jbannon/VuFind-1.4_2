<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/OverDrive/CollectionOverDriveIterator.php';
require_once dirname(__FILE__).'/../../mother/OverDriveAPI/resultsMother.php';

class CollectionOverDriveIteratorTests	extends PHPUnit_Framework_TestCase
{
	const accessToken = "aDummyAccessToken";
	const productsUrl = "aDummyProductsUrl";
	const totalItems = 302;
		
	private $service;
	private $overDriveAPIWrapperMock;
	private $resultsODAPIMother;
	
	public function setUp()
	{
		$this->resultsODAPIMother = new OverDriveResultsMother();
		$this->overDriveAPIWrapperMock = $this->getMock("IOverDriveAPIWrapper", array("getDigitalCollection"));
		$this->service = new CollectionOverDriveIterator(self::accessToken, self::productsUrl, self::totalItems, $this->overDriveAPIWrapperMock);
		parent::setUp();		
	}
	
	/**
	* method total
	* when called
	* should returnCorrectNumber
	*/
	public function test_total_called_returnCorrectNumber()
	{
		$expected = self::totalItems;
		$actual = $this->service->total();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method next
	* when called
	* should prepareNextCollectionBlock
	*/
	public function test_next_called_prepareNextCollectionBlock()
	{
		$expected = $this->service->getBlockSize();
		$this->service->next();
		$actual = $this->service->key();
		$this->assertSame($expected, $actual);
		
		$expected = $this->service->getBlockSize() * 3;
		$this->service->next();
		$this->service->next();
		$actual = $this->service->key();
		$this->assertSame($expected, $actual);
	}
	
	/**
	* method valid
	* when called
	* should shouldWorkCorrectly
	*/
	public function test_valid_called_shouldWorkCorrectly()
	{
		$blockSize = $this->service->getBlockSize();
		$totalItems = $blockSize + 1;
		
		$actual = $this->service->valid(); //0
		$this->assertTrue($actual);
		
		$this->service->next();
		$actual = $this->service->valid(); //0 + BlockSize
		$this->assertTrue($actual);
		
		$this->service->next(); //BlockSize + BlockSize FALSE!
		$actual = $this->service->valid();
		$this->assertFalse($actual);
	}
	
	/**
	* method current
	* when called
	* should returnArrayIterator
	*/
	public function test_current_called_returnArrayIterator()
	{
		$expected = 1;
		$digitalCollectionResult = $this->resultsODAPIMother->getDigitalCollectionResult();
		
		$this->overDriveAPIWrapperMock->expects($this->once())
									  ->method("getDigitalCollection")
									  ->with($this->equalTo(self::accessToken), $this->equalTo(self::productsUrl),
									  		 $this->equalTo($this->service->getBlockSize()), $this->equalTo($this->service->key()))
									  ->will($this->ReturnValue($digitalCollectionResult));
		
		$actual = $this->service->current();
		$this->assertEquals("ArrayIterator", get_class($actual));
		$this->assertEquals($expected, $actual->count());
	}

}

?>