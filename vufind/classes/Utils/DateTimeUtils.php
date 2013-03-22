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
		
		/**
		 * Calculate the timestamps for the first and last second of the week.
		 * If no timestamp given, the current timestamp is used.
		 * @param int $timestamp
		 * @return array 
		 */
		public static function getWeekStartAndEnd($timestamp = NULL) {
			if(!$timestamp) $timestamp = time();
			
			$today = date("l", $timestamp);
			if($today == 'Monday'){ $timestamp += 86400; }
			
			// start of the week is Monday
			$start = strtotime('last Monday', $timestamp);
			// end of the week is 7 days from Monday less 1 second
			$end = $start + (7 * 86400) - 1;
			
			if(date("l", $end) == 'Monday') //Spring Daylight Save
			{
				$end -= 3600;
			}
			if(date('l H:i:s', $end) == 'Sunday 22:59:59')  //Fall Daylight Save
			{
				$end += 3600;
			}
			
			return array($start, $end);
		}
	
	}

?>