<?php
require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/../../web/Drivers/Horizon.php';
require_once dirname(__FILE__).'/../../web/sys/Logger.php';

class HIPMonitor extends BaseMonitor
{
	private $patron;
	
	public function __construct($hipUrl, $hipProfile, $selfRegProfile, $username, $pwd)
	{
		global $configArray, $user;
		parent::__construct();
		
		$configArray['Catalog']['hipUrl'] = $hipUrl;
		$configArray['Catalog']['hipProfile'] = $hipProfile;
		$configArray['Catalog']['selfRegProfile'] = $selfRegProfile;
		$configArray['Catalog']['useDb'] = true;
		$configArray['System']['operatingSystem'] = 'windows';
		
		$this->patron['username'] = $username;
		$this->patron['cat_password'] = $pwd;
		$user->cat_username = $username;
		$user->cat_password = $pwd;
		
		$this->service = new Horizon();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$result = $this->service->getMyHoldsViaHip($this->patron);
		parent::afterExec();
		return is_array($result);
	}

}

?>