<?php

interface IOverDriveSSUtils{}

class OverDriveSSUtils implements IOverDriveSSUtils
{
	
	public function getSessionString($url)
	{
		if(preg_match("/[A-Z0-9]{8}\-[A-Z0-9]{4}\-[A-Z0-9]{4}\-[A-Z0-9]{4}\-[A-Z0-9]{12}/", $url, $matched))
		{
			return $matched[0];
		}
		return '';
	}
	
}

?>