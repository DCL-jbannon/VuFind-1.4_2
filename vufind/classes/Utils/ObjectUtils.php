<?php

class ObjectUtils
{
	static public function is_a($object, $className)
	{
		if(is_object($object))
		{
			if (get_class($object) == $className)
			{
				return true;
			}
		}
		return false;
	}
}

?>