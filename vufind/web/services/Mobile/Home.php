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
require_once 'services/API/ListAPI.php';

class Home extends Action {

	function launch()
	{
		global $interface;
		global $configArray;
		global $library;
		global $locationSingleton;
		global $timer;
		global $user;
		global $memcache;

		// Include Search Engine Class
		require_once 'sys/' . $configArray['Index']['engine'] . '.php';
		$timer->logTime('Include search engine');
		if ($user)
		{
			$catalog = new CatalogConnection($configArray['Catalog']['driver']);
			$patron = $catalog->patronLogin($user->cat_username, $user->cat_password);
			$profile = $catalog->getMyProfile($patron);
			if (!PEAR::isError($profile))
			{
				$interface->assign('profile', $profile);
			}
		}
		
		if($listTitlesNE = $memcache->get('latestACSOverDriveItems') ===  FALSE )
		{
			//Get AVG Rating eContent
			$listAPI = new ListAPI();
			//New Ebooks
			$listTitlesNE = $listAPI->getListTitles('newACSOverDriveItems');//Check if the list is empty or not
			$memcache->set("newACSOverDriveItems", $listTitlesNE, 0, 3600);
		}
		//Assign lists to Smarty var
		$interface->assign('NE',(!empty($listTitlesNE['titles']) ? $listTitlesNE['titles'] : ""));
		
		// Cache homepage
		$interface->caching = 1;
		$cacheId = 'homepage|' . $interface->lang;
		//Disable Home page caching for now.
		if (!$interface->is_cached('layout.tpl', $cacheId)) {
			$interface->setPageTitle('iDCLReader Catalog Home');
			$interface->setTemplate('home.tpl');
		}
		$interface->display('layout.tpl', $cacheId);
	}
}