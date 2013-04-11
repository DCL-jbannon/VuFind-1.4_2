<?php
global $configArray;
require_once dirname(__FILE__).'/bootstrap.php';

$execTime = 0;
switch($_GET['method'])
{
	case "all":
		$service[] = array(getMemcacheMonitor($config), "memcache");
		$service[] = array(getCoversServerMonitor($config), "covers");
		$service[] = array(getFreegalMonitor($config), "freegal_covers");
		$service[] = array(getGoogleCoversMonitor($config), "google_covers");
		$service[] = array(getThingCoversMonitor($config), "thing_covers");
		$service[] = array(getSyndeticsMonitor($config), "syndetics");
		$service[] = array(getHIPMonitor($config), "hip");
		$service[] = array(getSIPMonitor($config), "sip");
		$service[] = array(getMysqlMonitor($config), "mysql");
		$service[] = array(getOverDriveMonitor($config), "overdrive");
		$service[] = array(get3MMonitor($config), "3m");
		$service[] = array(getSolrMonitor($config), "solr");
		$service[] = array(getHIPDBMonitor($config), "hipdb");
		break;
	case "memcache";
	$service = getMemcacheMonitor($config);
	break;
	case "covers";
		$service = getCoversServerMonitor($config);
	break;
	case "freegal_covers";
		$service = getFreegalMonitor($config);
	break;
	case "google_covers";
		$service = getGoogleCoversMonitor($config);
	break;
	case "thing_covers";
		$service = getThingCoversMonitor($config);
	break;
	case "syndetics";
		$service = getSyndeticsMonitor($config);
	break;
	case "hip";
		$service = getHIPMonitor($config);
	break;
	case "hipdb";
	$service = getHIPDBMonitor($config);
	break;
	case "sip";
		$service = getSIPMonitor($config);
   	break;
	case "mysql";
		$service = getMysqlMonitor($config);
	break;
	case "overdrive";
		$service = getOverDriveMonitor($config);
	break;
	case "3m";
		$service = get3MMonitor($config);
	break;
	case "solr";
		$service = getSolrMonitor($config);
	break;
	default:
		echo "<h1>The method ".$_GET['method']." is not valid</h1></br>";
		echo "<h2>Methods valids:</h2><br/>";
		getInformation();
		exit(0);
	break;
}

if(!is_array($service))
{
	$service->exec();
	$time = getFormatTime($service->getExecutionTime());
	saveStats($_GET['method'], $time, $configArray);
	echo $_GET['method'].": ".$time;
}
else
{
	foreach($service as $monitorInfo)
	{
		$monitorInfo[0]->exec();
		$time = getFormatTime($monitorInfo[0]->getExecutionTime());
		saveStats($monitorInfo[1], $time, $configArray);
		echo $monitorInfo[1].": ".$time."<br/>";
	}
}
	
function getFormatTime($time)
{
	return number_format((float)$time, 4, ".", ",");
}

function saveStats($method, $time, $config)
{
	global $trans;
	$output = array();
	if ($config['System']['operatingSystem'] == 'windows')
	{
		exec(dirname(__FILE__)."/rrdtool/rrdtool.exe updatev ./bbdd/".$method.".rrd N:".$time."", &$output);
	}
	else
	{
		exec('/usr/bin/rrdtool updatev /var/www/VuFind-Plus/vufind/monitor/bbdd'.$method.".rrd N:".$time."", &$output);
	}
	//exec(dirname(__FILE__).'/rrdtool/rrdtool.exe graphv ./graphs/'.$method.'.png -a PNG --title="'.$trans[$method].' Response Time" --start=end-24h --end=now --vertical-label "Response Time" "DEF:resp_time='.$method.'.rrd:'.$method.':AVERAGE" "GPRINT:resp_time:AVERAGE:\'%.0lf sec\'" AREA:resp_time#DD000044:"Response Time" --width=800 --height=250', &$output2);
	//var_dump($output);
}

function getSolrMonitor($config)
{
  return new SolrMonitor($config['Solr']['IndexUrl'], 
								         $config['Solr']['IndexCore']);
}

function get3MMonitor($config)
{
  return new ThreeMMonitor($config['3MAPI']['url'],
									 $config['3MAPI']['libraryId'],
									 $config['3MAPI']['accesKey'],
									 $config['3MAPI']['itemId']);
}

function getOverDriveMonitor($config)
{
  return new OverDriveMonitor($config['OverDriveAPI']['itemId']);
}

function getMysqlMonitor($config)
{
    return new MysqlMonitor(
									$config['MySQL']['DBHost'],
									$config['MySQL']['DBName'],
									$config['MySQL']['DBUsr'],
									$config['MySQL']['DBPwd'],
									$config['MySQL']['SQL']
	               );
}

function getSIPMonitor($config)
{
  return new SIP2Monitor($config['SIP2']['host'],
								   $config['SIP2']['port'],
								   $config['HIP']['patronId'],
								   $config['HIP']['pwd']);
}

function getHIPMonitor($config)
{
	return new HIPMonitor($config['HIP']['hipUrl'],
								  $config['HIP']['hipProfile'],
								  $config['HIP']['selfRegProfile'],
								  $config['HIP']['patronId'],
								  $config['HIP']['pwd']);
}

function getHIPDBMonitor($config)
{
	return new HIPDBMonitor($config['HIP']['hipUrl'],
							$config['HIP']['hipProfile'],
							$config['HIP']['selfRegProfile'],
							$config['HIP']['patronId'],
							$config['HIP']['pwd']);
}

function getSyndeticsMonitor($config)
{
	return new SyndeticsMonitor($config['Syndetics']['imageUrl']);
}

function getThingCoversMonitor($config)
{
	return new LibraryThingMonitor($config['LibraryThing']['imageUrl']);
}

function getGoogleCoversMonitor($config)
{
	return new GoogleMonitor($config['Google']['imageUrl']);
}

function getFreegalMonitor($config)
{
	return new FreegalMonitor($config['FreeGal']['freegalUrl'], 
									  $config['FreeGal']['freegalAPIkey'], 
									  $config['FreeGal']['libraryId'],
									  $config['FreeGal']['patronId']);
}

function getCoversServerMonitor($config)
{
	return new CoversDirectoryMonitor($config['CoversServer']['path']);
}

function getMemcacheMonitor($config)
{
	return new MemcacheMonitor($config['Memcache']['hostname']);
}

?>