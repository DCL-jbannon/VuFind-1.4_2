<?php
require_once dirname(__FILE__).'/OverDriveAPIException.php';
require_once dirname(__FILE__).'/OverDriveAPIWrapper.php';

class CollectionOverDriveIterator implements Iterator {
	private $blockSize = 300;
	
	private $offset;
	
	private $accessToken;
	private $total;
	private $productsUrl;
	private $odw;

	public function __construct($accessToken, $productsUrl, $total, IOverDriveAPIWrapper $overDriveAPIWrapper = NULL, $blockSize = NULL)
	{
		
		$this->accessToken = $accessToken;
		$this->productsUrl = $productsUrl;
		$this->total = $total;
		
		if(!$overDriveAPIWrapper) $overDriveAPIWrapper = new OverDriveAPIWrapper();
		$this->odw = $overDriveAPIWrapper;
		
		$this->offset = 0;
		if ($blockSize !== NULL)
		{
			if (intval($blockSize) > 0)
			{
				$this->blockSize = $blockSize;
			}
		}
	}

	public function rewind() {
		$this->offset = 0;
	}

	public function current() {
		$result = $this->odw->getDigitalCollection($this->accessToken, $this->productsUrl, 
												   $this->blockSize, $this->offset);
		return new ArrayIterator($result->products);
	}

	public function key() {
		return $this->offset;
	}

	public function next() {
		 $this->offset += $this->blockSize;
	}

	public function valid() {
		if($this->total() > ($this->key()) )
		{
			return true;
		}
		return false;
	}
	
	public function total()
	{
		return $this->total;
	}
	
	public function getBlockSize()
	{
		return $this->blockSize;
	}
}

?>