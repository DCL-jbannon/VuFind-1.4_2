<?php

	class DateTimeUtils
	{
	
		/**
		 * 2 Weeks Timestamp to append to bookcover URL
		 * @param unknown_type $timestamp
		 */
		public static function getTSBookCover($timestamp = NULL)
		{
			if(!$timestamp) $timestamp = mktime();
			return gmdate("mY", $timestamp);
		}
	
	}

?>