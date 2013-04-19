<?php

interface INovelistWrapper{}

class NovelistWrapper implements INovelistWrapper
{

	private $profile;
	private $password;
	
	const urlContentByQuery = "http://novselect.ebscohost.com/Data/ContentByQuery?profile={PUT_HERE_PROFILE}&password={PUT_HERE_PASSWORD}&ClientIdentifier={PUT_HERE_ISBN}&ISBN={PUT_HERE_ISBN}&callback=&indent=true&tmstmp={PUT_HERE_TS}&version=2.1";
	
	public function __construct()
	{
		global $configArray;

		$this->profile = $configArray['NovelistAPI']['profile'];
		$this->password = $configArray['NovelistAPI']['password'];
	}	

	public function getInfoByISBN($isbn)
	{

		$url = str_replace("{PUT_HERE_PROFILE}", $this->profile, self::urlContentByQuery);
		$url = str_replace("{PUT_HERE_PASSWORD}", $this->password, $url);
		$url = str_replace("{PUT_HERE_ISBN}", $isbn, $url);
		$url = str_replace("{PUT_HERE_TS}", mktime(), $url);

		return json_decode(file_get_contents($url));		
	}

}
?>