<?php

require_once dirname(__FILE__).'/Checkout.php';

class CFormat extends Checkout
{
	
	public function __construct()
	{
		parent::__construct();
		$this->chooseFormat = true;
	}

}

?>