<?php
/**
 *
 * Copyright (C) Villanova University 2009.
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

/**
 * Support function -- get the file path to one of the ini files specified in the
 * [Extra_Config] section of config.ini.
 *
 * @param   name        The ini's name from the [Extra_Config] section of config.ini
 * @return  string      The file path
 */
function getExtraConfigArrayFile($name)
{
	global $configArray;

	// Load the filename from config.ini, and use the key name as a default
	//     filename if no stored value is found.
	$filename = isset($configArray['Extra_Config'][$name]) ? $configArray['Extra_Config'][$name] : $name . '.ini';

	//Check to see if there is a domain name based subfolder for he configuration
	global $servername;
	if (file_exists("../../sites/$servername/conf/$filename")){
		// Return the file path (note that all ini files are in the conf/ directory)
		return "../../sites/$servername/conf/$filename";
	}elseif (file_exists("../../sites/default/conf/$filename")){
		// Return the file path (note that all ini files are in the conf/ directory)
		return "../../sites/default/conf/$filename";
	} else{
		// Return the file path (note that all ini files are in the conf/ directory)
		return '../../sites/' . $filename;
	}

}

/**
 * Support function -- get the contents of one of the ini files specified in the
 * [Extra_Config] section of config.ini.
 *
 * @param   name        The ini's name from the [Extra_Config] section of config.ini
 * @return  array       The retrieved configuration settings.
 */
function getExtraConfigArray($name)
{
	static $extraConfigs = array();

	// If the requested settings aren't loaded yet, pull them in:
	if (!isset($extraConfigs[$name])) {
		// Try to load the .ini file; if loading fails, the file probably doesn't
		// exist, so we can treat it as an empty array.
		$extraConfigs[$name] = @parse_ini_file(getExtraConfigArrayFile($name), true);
		if ($extraConfigs[$name] === false) {
			$extraConfigs[$name] = array();
		}

		if ($name == 'facets'){
			//*************************
			//Marmot overrides for controlling facets based on library system.
			global $librarySingleton;
			$library = $librarySingleton->getActiveLibrary();
			if (isset($library)){
				if (strlen($library->defaultLibraryFacet) > 0 && $library->useScope){
					unset($extraConfigs[$name]['Results']['institution']);
					unset($extraConfigs[$name]['Author']['institution']);
				}
			}
			global $locationSingleton;
			$activeLocation = $locationSingleton->getActiveLocation();
			if (!is_null($activeLocation)){
				if (strlen($activeLocation->defaultLocationFacet) && $activeLocation->useScope){
					unset($extraConfigs[$name]['Results']['institution']);
					unset($extraConfigs[$name]['Results']['building']);
					unset($extraConfigs[$name]['Author']['institution']);
					unset($extraConfigs[$name]['Author']['building']);
				}
			}
		}
	}

	return $extraConfigs[$name];
}

/**
 * Support function -- merge the contents of two arrays parsed from ini files.
 *
 * @param   config_ini  The base config array.
 * @param   custom_ini  Overrides to apply on top of the base array.
 * @return  array       The merged results.
 */
function ini_merge($config_ini, $custom_ini)
{
	foreach ($custom_ini as $k => $v) {
		if (is_array($v)) {
			$config_ini[$k] = ini_merge(isset($config_ini[$k]) ? $config_ini[$k] : array(), $custom_ini[$k]);
		} else {
			$config_ini[$k] = $v;
		}
	}
	return $config_ini;
}

/**
 * Support function -- load the main configuration options, overriding with
 * custom local settings if applicable.
 *
 * @return  array       The desired config.ini settings in array format.
 */
function readConfig()
{
	//Read default configuration file
	$configFile = dirname(__FILE__)."/../../../sites/default/conf/config.ini";
	$mainArray = parse_ini_file($configFile, true);
	
	global $servername;
	$serverUrl = $_SERVER['SERVER_NAME'];
	$server = $serverUrl;
	$serverParts = explode('.', $server);
	$servername = 'default';
	while (count($serverParts) > 0){
		$tmpServername = join('.', $serverParts);
		$configFile = dirname(__FILE__)."/../../../sites/$tmpServername/conf/config.ini";
		if (file_exists($configFile)){
			$serverArray = parse_ini_file($configFile, true);
			$mainArray = ini_merge($mainArray, $serverArray);
			$servername = $tmpServername;
		}
		array_shift($serverParts);
	}
	
	if ($servername == 'default'){
		$logger = new Logger();
		$logger->log('Did not find servername for server ' . $_SERVER['SERVER_NAME'], PEAR_LOG_ERR);
		PEAR::raiseError("Invalid configuration, could not find site for " . $_SERVER['SERVER_NAME']);
	}
	
	if ($mainArray == false){
		echo("Unable to parse configuration file $configFile, please check syntax");
	}
	//If we are accessing the site via a subdomain, need to preserve the subdomain
	if (isset($_SERVER['HTTPS'])){
		$mainArray['Site']['url'] = "https://" . $serverUrl;
	}else{
		$mainArray['Site']['url'] = "http://" . $serverUrl;
	}
	
	if (isset($mainArray['Extra_Config']) &&
	isset($mainArray['Extra_Config']['local_overrides'])) {
		if (file_exists("../../sites/$servername/conf/" . $mainArray['Extra_Config']['local_overrides'])){
			$file = trim("../../sites/$servername/conf/" . $mainArray['Extra_Config']['local_overrides']);
			$localOverride = @parse_ini_file($file, true);
		}else {
			$file = trim('../../sites/default/conf/' . $mainArray['Extra_Config']['local_overrides']);
			$localOverride = @parse_ini_file($file, true);
		}
		if ($localOverride) {
			return ini_merge($mainArray, $localOverride);
		}
	}
	return $mainArray;
}