<?php
class RequestUtils
{	
	public static function getRequest($name)
	{
		if(!isset($_REQUEST[$name]))
		{
			return "";
		}
		return $_REQUEST[$name];
	}
	
	public static function getGet($name)
	{
		if(!isset($_GET[$name]))
		{
			return "";
		}
		return $_GET[$name];
	}
}
?>