<?php

interface IPaginationUtils{}

class PaginationUtils implements IPaginationUtils
{
	public static function getNumPageByStartRecordNumberRecords($start, $numRecords)
	{
		if($start<=$numRecords) return 1;
		
		return ceil($start/$numRecords);
	}
		
}

?>