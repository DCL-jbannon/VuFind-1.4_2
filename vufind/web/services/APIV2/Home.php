<?php
require_once 'Action.php';
require_once dirname(__FILE__).'/../../../classes/API/Server/ServerAPI.php';

class Home extends Action
{
	
	public function launch()
	{
		global $configArray;
		$headers = apache_request_headers();
		
		$content = file_get_contents('php://input');
		
		$service = new ServerAPI($configArray['ServerAPI']['secretKey']);
		$result = $service->run($headers, $content);
		
		header("Content-type: application/json");
		echo $result;
		die();
	}
	
}