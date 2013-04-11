<?php
require_once dirname(__FILE__).'/bootstrap.php';

if(!in_array($_GET['method'], $validMethods))
{
	echo "<h1>The method ".$_GET['method']." is not valid</h1></br>";
	echo "<h2>Methods valids:</h2><br/>";
	getInformation();
	exit(0);
}

$method = $_GET['method'];
$ago = "24";
if(isset($_GET['ago']))
{
	$ago = $_GET['ago'];
}



if ($config['System']['operatingSystem'] == 'windows')
{	
	$filename = './graphs/'.$method.'-'.$ago.'.png';
	$commandToRun = dirname(__FILE__).'/rrdtool/rrdtool.exe graphv '.$filename.' -a PNG '
							   		 .'--title="'.$trans[$method].' Response Time" --start=now-'.$ago.'h --end=now --vertical-label "Response Time" '
								     .'"DEF:resp_time=bbdd/'.$method.'.rrd:'.$method.':AVERAGE" '
								     .'"GPRINT:resp_time:AVERAGE:\'%.0lf sec\'" '
								     .'AREA:resp_time#FF0000:"Response Time" '
								     .'--width=600 --height=200';
}
else
{
	$filename = '/var/www/VuFind-Plus/vufind/monitor/graphs/'.$method.'-'.$ago.'.png';
	$commandToRun = '/usr/bin/rrdtool graphv '.$filename.' -a PNG '
					.'--title="'.$trans[$method].' Response Time" --start=now-'.$ago.'h --end=now --vertical-label "Response Time" '
					.'"DEF:resp_time=bbdd/'.$method.'.rrd:'.$method.':AVERAGE" '
					.'"GPRINT:resp_time:AVERAGE:\'%.0lf sec\'" '
					.'AREA:resp_time#FF0000:"Response Time" '
					.'--width=600 --height=200';
}

$out = array();
exec($commandToRun, &$out);
//var_dump($commandToRun);
//die();
$rawImage = file_get_contents($filename);
header('Content-Type: image/png');
echo $rawImage;

?>