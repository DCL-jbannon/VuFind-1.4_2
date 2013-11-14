<?php
/**
 *
 * Copyright (C) Villanova University 2007.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */

require_once 'services/Report/AnalyticsReport.php';
require_once 'sys/Pager.php';
require_once("PHPExcel.php");

class Searches extends AnalyticsReport
{

	function launch()
	{
		global $configArray;
		global $interface;
		global $user;
		
		$startDate = date("m/d/Y", mktime()-86400*7);
		$endDate = date("m/d/Y", mktime()-86400);
		
		if (isset($_POST) && !empty($_POST))
		{
			if(empty($_POST['dateFilterStart']) || empty($_POST['dateFilterEnd']))
			{
				$interface->assign("msgError", "Please, select 'Start Date' and 'End Date'");
			}
			else
			{
				$startDate = $_POST['dateFilterStart'];
				$endDate = $_POST['dateFilterEnd'];
			}
		}
		
		$interface->assign("startDate", $startDate);
		$interface->assign("endDate", $endDate);
		
		$startDateSQL = $this->getSQLDateFormat($startDate)." 00:00:00";
		$endDateSQL = $this->getSQLDateFormat($endDate)." 23:59:59";
		
		$startDateTimeStamp = strtotime($startDateSQL);
		$endDateTimeStamp = strtotime($endDateSQL);		

		
		set_time_limit(0);
		
		//Setup filters
		$this->setupFilters();

		$search = new Analytics_Search();
		$search->selectAdd();
		$search->selectAdd("count(id) as timesSearched");
		$search->selectAdd("lookfor");
		$search->whereAdd("numResults > 0");
		
		$search->whereAdd("searchTime >= ".$startDateTimeStamp);
		$search->whereAdd("searchTime <= ".$endDateTimeStamp);
		
		$search->groupBy('lookfor');
		$search->orderBy('timesSearched DESC');
		$search->limit(0, 20);
		$search->find();
		$topSearches = array();
		while ($search->fetch()){
			if (!is_null($search->lookfor) || strlen(trim($search->lookfor)) > 0){
				$topSearches[] = "{$search->lookfor} ({$search->timesSearched})";
			}else{
				$topSearches[] = "<blank> ({$search->timesSearched})";
			}
			echo " ";
			flush();
		}
		$interface->assign('topSearches', $topSearches);

		echo " ";
		flush();
		set_time_limit(0);
		
		$search = new Analytics_Search();
		$search->selectAdd();
		$search->selectAdd("count(id) as timesSearched");
		$search->selectAdd("lookfor");
		$search->whereAdd("numResults = 0");
		
		$search->whereAdd("searchTime >= ".$startDateTimeStamp);
		$search->whereAdd("searchTime <= ".$endDateTimeStamp);
		
		$search->groupBy('lookfor');
		$search->orderBy('timesSearched DESC');
		$search->limit(0, 20);
		$search->find();
		$topNoHitSearches = array();
		while ($search->fetch()){
			if (!is_null($search->lookfor) || strlen(trim($search->lookfor)) > 0){
				$topNoHitSearches[] = "{$search->lookfor} ({$search->timesSearched})";
			}else{
				$topNoHitSearches[] = "<blank> ({$search->timesSearched})";
			}
			echo " ";
			flush();
		}
		$interface->assign('topNoHitSearches', $topNoHitSearches);

		echo " ";
		flush();
		set_time_limit(0);
		
		$search = new Analytics_Search();
		$search->selectAdd();
		$search->selectAdd("lookfor");
		$search->selectAdd("MAX(searchTime) as lastSearch ");
		
		$search->whereAdd("searchTime >= ".$startDateTimeStamp);
		$search->whereAdd("searchTime <= ".$endDateTimeStamp);
		
		$search->groupBy('lookfor');
		$search->orderBy('lastSearch DESC');
		$search->limit(0, 20);
		$search->find();
		$latestSearches = array();
		while ($search->fetch()){
			if (!is_null($search->lookfor) || strlen(trim($search->lookfor)) > 0){
				$latestSearches[] = "{$search->lookfor} {$search->searchTime}";
			}else{
				$latestSearches[] = "<blank>";
			}
			echo " ";
			flush();
		}
		$interface->assign('latestSearches', $latestSearches);

		echo " ";
		flush();
		set_time_limit(0);
		
		$search = new Analytics_Search();
		$search->selectAdd();
		$search->selectAdd("lookfor");
		$search->selectAdd("MAX(searchTime) as lastSearch ");
		$search->whereAdd("numResults = 0");
		
		$search->whereAdd("searchTime >= ".$startDateTimeStamp);
		$search->whereAdd("searchTime <= ".$endDateTimeStamp);
		
		$search->groupBy('lookfor');
		$search->orderBy('lastSearch DESC');
		$search->limit(0, 20);
		$search->find();
		$latestNoHitSearches = array();
		while ($search->fetch()){
			if (!is_null($search->lookfor) || strlen(trim($search->lookfor)) > 0){
				$latestNoHitSearches[] = "{$search->lookfor} {$search->searchTime}";
			}else{
				$latestNoHitSearches[] = "<blank>";
			}
			echo " ";
			flush();
		}
		$interface->assign('latestNoHitSearches', $latestNoHitSearches);

		$interface->setPageTitle('Report - Searches');
		$interface->setTemplate('searches.tpl');
		$interface->display('layout.tpl');
	}
	
	private function getSQLDateFormat($date)
	{
		$partsDate = explode("/", $date);
		return $partsDate[2]."-".$partsDate[0]."-".$partsDate[1];
	}
}