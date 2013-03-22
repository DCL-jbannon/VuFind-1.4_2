<?php
require_once dirname(__FILE__).'/BaseMonitor.php';
require_once dirname(__FILE__).'/../../web/Drivers/Horizon.php';
require_once dirname(__FILE__).'/../../web/Drivers/DCL.php';
require_once dirname(__FILE__).'/../../web/sys/Logger.php';

class HIPDBMonitor extends BaseMonitor
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
		//$configArray['System']['operatingSystem'] = 'windows';
		
		$this->patron['username'] = $username;
		$this->patron['cat_password'] = $pwd;
		$user->cat_username = $username;
		$user->cat_password = $pwd;
		
		$this->service = new DCL();
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$result = $this->service->getMyFinesViaDB($this->patron);
		parent::afterExec();
		return is_array($result);
	}

}

?>