<?php

class UniqueIdentifier
{
	
	public static function get($prefix = NULL)
	{
		if($prefix !== NULL)
		{
			$prefix = str_replace("-","", $prefix);
			$prefix .= "-";
		}
		
		
		$result = uniqid($prefix, true);
		$result	= str_replace(".", "-", $result);
		return $result;
	}	
	
}