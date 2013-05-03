<?php
require_once 'PEAR.php';
require_once dirname(__FILE__).'/../web/sys/Logger.php';
require_once dirname(__FILE__).'/../web/sys/ConfigArray.php';

global $servername;
global $configArray;

if(!isset($argv[1]))
{
	die("Please define the site name to run this process");
}

$servername = $argv[1];
$_SERVER['SERVER_NAME'] = $servername;

$configArray = readConfig();
$attachFilesPath = $configArray['EContent']['rootFTPDir'];

$cronPath = $configArray['Site']['cronPath'];
if ($configArray['System']['operatingSystem'] == 'windows'){
	$commandToRun = "cd $cronPath && start /b java -jar cron.jar $servername org.epub.EcontentAttachments";
}else{
	$commandToRun = "cd {$cronPath}; java -jar cron.jar $servername org.epub.EcontentAttachments";
}

$connString = $configArray['Database']['database_econtent'];
preg_match("/mysql:\/\/(.*):/", $connString, $matches);
preg_match("/^mysql:\/\/[a-zA-Z]*:(.*)@/", $connString, $matches2);
preg_match("/@(.*)\//", $connString, $matches3);
preg_match("/@(.*)\/(.*)/", $connString, $matches4);
$user = $matches[1];
$password = $matches2[1];
$host = $matches3[1];
$db = $matches4[2];

$link = mysql_connect($host,$user,$password);
mysql_select_db($db,$link);
$query = "SELECT source, attachPath FROM econtent_record_detection_settings WHERE accessType='acs' and attachPath != ''";
$result = mysql_query($query,$link);
if (mysql_num_rows($result) == 0) {
    die('No ACS with attachPath information defined: ' . mysql_error());
}

while($row = mysql_fetch_assoc($result))
{
	$cmd = $commandToRun." sourcePath=\"".$attachFilesPath."/".$row['attachPath']."\" source=\"".$row['source']."\"";
	$handle = popen($cmd, 'r');
	echo "\nRunning ".$cmd."\n";
	pclose($handle);
	echo "\nRunned ".$cmd."\n";
	sleep(10);
}

echo "\nEND\n";

?>