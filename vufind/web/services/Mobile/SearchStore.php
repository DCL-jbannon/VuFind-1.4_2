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
* @author Juan Gimenez <jgimenez@dclibraries.org>
*
*/

require_once 'Action.php';
require_once dirname(__FILE__).'/../../../classes/SolrDriver.php';
require_once dirname(__FILE__).'/../../../classes/Utils/UrlUtils.php';
require_once dirname(__FILE__).'/../../../classes/Utils/BookCoverURL.php';
require_once dirname(__FILE__).'/../../../classes/solr/SolrResponse.php';

class SearchStore extends Action {

	function launch()
	{
		global $interface;
		global $configArray;
		global $library;
		global $locationSingleton;
		global $timer;
		global $user;
		global $memcache;
		global $configArray;
		
		$urlUtils = new UrlUtils();
		$interface->assign('ButtonBack',true);
		$interface->assign('ButtonHome',true);
		$interface->setPageTitle('Search');
		$interface->assign('MobileTitle','Search '.$_GET['lookfor']);
		
		$search = ( empty($_GET['lookfor']) ? "*:*" :$_GET['lookfor']);
		
		//Starting the Solr Search
		$extraParam = array(
				              'hl' => 'true',
				              'hl.fl' => 'title author publishDate isbn origin',
							  'defType' => 'dismax',
							  'qf' => 'title author publishDate description isbn issn origin',
							  'fl' => 'title author publishDate description isbn issn origin',
				              'tie' => '1.01',	
							  'df' => 'text',	
							  'pf' => 'title^400 author^300 publishDate^150 description^100 issn^50 issn^50 origin^1',
				         	  'ps' => '0'); 		  
		
		$start = 0;
		if(isset($_GET['start']))
		{
			$start = intval($_GET['start']);
		}
		
		$extraParamString = $urlUtils->getParamsString($extraParam);
		$solrDriver = new SolrDriver($configArray['SOLR_STORE']['url'], $configArray['SOLR_STORE']['index']);
		$result = $solrDriver->search($search, $start, 20, $extraParamString);
		//Solr Processing
		$solrResponse = new SolrResponse();
		$solrResponse->set($result);
		if ($solrResponse->getNumDocs() == 0)
		{
			$interface->assign("lookfor", $solrResponse->getQueryString());
			$interface->setTemplate('Search/list-none.tpl');
		}
		else
		{
			$bookCoverUrl = new BookcoverURL();
			$bookCoverUrl->setBaseUrl($configArray['Site']['coverUrl']);
			$docs = $solrResponse->getDocs();
			/* @var $docs ArrayIterator */
			$docs->rewind();
			$recordSet = array();
			$i=0;
			while ($docSolr = $docs->current())
			{
				/* @var $docSolr SolrDOC */
				$recordSet[$i]['docSolr'] = $docSolr;
				$recordSet[$i]['query'] = $solrResponse->getQueryString();
				$recordSet[$i]['bookCoverUrl'] = $bookCoverUrl->getBookCoverUrl("small", $docSolr->getISSN(), $docSolr->getMysqlId(), true);
				$docs->next();
				$i++;
			}

			//Prepare NextLink and PrevLink
				$rows = $solrResponse->getRows();
				$prevStart = $solrResponse->getPrevStart();
				$nextStart = $solrResponse->getNextStart();
				
				$interface->assign("prevStart",$prevStart);
				$interface->assign("nextStart",$nextStart);
				$interface->assign("rows",$rows);
				$interface->assign("query",$solrResponse->getQueryString());
			
			
			$interface->assign("recordSet",$recordSet);
			$interface->setTemplate('Search/list.tpl');
		}
		
		
		$interface->display('layout.tpl');
	}
}