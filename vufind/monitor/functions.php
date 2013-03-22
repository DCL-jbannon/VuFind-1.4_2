<?php

function getInformation()
{
	echo "Covers Server.  Method name: <strong>covers</strong>.</br>";
	echo "Freegal Covers.  Method name: <strong>freegal_covers</strong>.</br>";
	echo "Google Covers.  Method name: <strong>google_covers</strong>.</br>";
	echo "LibraryThing Covers.  Method name: <strong>thing_covers</strong>.</br>";
	echo "Syndetics Covers.  Method name: <strong>syndetics</strong>.</br>";
	echo "HIP. Method name: <strong>hip</strong>.</br>";
	echo "HIP - DB. Method name: <strong>hipdb</strong>.</br>";
	echo "SIP.  Method name: <strong>sip</strong>.</br>";
	echo "MySQL.  Method name: <strong>mysql</strong>.</br>";
	echo "OverDrive.  Method name: <strong>overdrive</strong>.</br>";
	echo "3M.  Method name: <strong>3m</strong>.</br>";
	echo "Solr.  Method name: <strong>solr</strong>.</br>";
}


$validMethods = array('memcache', 'covers', 'freegal_covers', 'google_covers', 'thing_covers', 'syndetics',
		'hip', 'sip', 'mysql', 'overdrive', '3m', 'solr', 'hipdb'
);


$trans['memcache'] = "Memcache";
$trans['covers'] = "Transfer Server";
$trans['freegal_covers'] = "Freegal API";
$trans['google_covers'] = "Google Covers";
$trans['thing_covers'] = "LibraryThing Covers";
$trans['syndetics'] = "Syndetics Covers";
$trans['hip'] = "HIP";
$trans['sip'] = "SIP2";
$trans['mysql'] = "MySQL";
$trans['overdrive'] = "OverDrive API";
$trans['3m'] = "3M API";
$trans['solr'] = "Solr";
$trans['hipdb'] = "HIP - DB Sybase";
$trans['hipdb'] = "HIP - DB MSSQL";
?>