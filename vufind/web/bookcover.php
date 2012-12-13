<?php
/**
 *
 * Copyright (C) Douglas County Libraries
 * @author Juan Giménez <jgimenez@dclibraries.org>
 *
 */
require_once dirname(__FILE__).'/../classes/services/EContentRecordServices.php';
require_once dirname(__FILE__).'/../classes/covers/AttachedEcontentCovers.php';
require_once dirname(__FILE__).'/../classes/covers/LibraryThingCovers.php';
require_once dirname(__FILE__).'/../classes/covers/GoogleBooksCovers.php';
require_once dirname(__FILE__).'/../classes/covers/OpenLibraryCovers.php';
require_once dirname(__FILE__).'/../classes/covers/SyndeticsCovers.php';
require_once dirname(__FILE__).'/../classes/covers/OverDriveCovers.php';
require_once dirname(__FILE__).'/../classes/covers/FreeGalCovers.php';
require_once dirname(__FILE__).'/services/MyResearch/lib/Resource.php';
require_once dirname(__FILE__).'/../classes/covers/CoversType.php';
require_once dirname(__FILE__).'/sys/eContent/EContentRecord.php';
require_once 'sys/ConfigArray.php';
require_once 'sys/Logger.php';
require_once 'sys/Timer.php';

global $logger, $timer, $bookCoverPath, $localFile;

/**BEGIN INIT**/
$timer = new Timer(microtime(false));
$configArray = readConfig();
$logger = new Logger();
$bookCoverPath = $configArray['Site']['coverPath'];

if ( (count($_GET) === 0) || ( !isset($_GET['id']) || empty($_GET['id']) ) )
{
	$localFile = $bookCoverPath.'/large/invalid.jpg';
	dieWithFailImage();
}

$size = ((isset($_GET['size']) ? $_GET['size'] : "large"));
$id = $_GET['id'];
$nameFile = (isEcontentBookCover() ? "eContent" : "").$id;
$localFile = $bookCoverPath.'/'.$size.'/'.$nameFile.'.jpg';

if(!isset($_GET['reload']))
{
	//Add caching information
	$expires = 60*60*24*14;  //expire the cover in 2 weeks on the client side
	header("Cache-Control: maxage=".$expires);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
	
	if (file_exists($localFile))
	{
		$logger->log("The Cover already exists ".$localFile, PEAR_LOG_INFO);
		processImageURL($localFile, $size);
		exit();
	}
}

// Setup Local Database Connection
if (!defined('DB_DATAOBJECT_NO_OVERLOAD')){
	define('DB_DATAOBJECT_NO_OVERLOAD', 0);
}
$options =& PEAR::getStaticProperty('DB_DataObject', 'options');
$options = $configArray['Database'];

/**BEGIN INIT**/

//Check if eContent
if(isEcontentBookCover())
{
	$basePathOriginalCovers = $bookCoverPath . '/original/';
	
	$baseFreeGalUrl = $configArray['FreeGal']['freegalUrl'];
	$apiKey = $configArray['FreeGal']['freegalAPIkey'];
	$libraryId = $configArray['FreeGal']['libraryId'];
	$patronID = $configArray['FreeGal']['patronId'];
	
	$eContentCoversProcessors[0]['name'] = "AttachedEcontentCovers";
	$eContentCoversProcessors[0]['instance'] = new AttachedEcontentCovers($basePathOriginalCovers);
	$eContentCoversProcessors[1]['name'] = "OverDriveCovers";
	$eContentCoversProcessors[1]['instance'] = new OverDriveCovers();
	$eContentCoversProcessors[2]['name'] = "FreeGalCovers";
	$eContentCoversProcessors[2]['instance'] = new FreeGalCovers($baseFreeGalUrl, $apiKey, $libraryId, $patronID);;
	
	foreach ($eContentCoversProcessors as $processor)
	{
		try
		{
			$logger->log("Checking Cover for ".$processor['name'], PEAR_LOG_INFO);
			$imageUrl = $processor['instance']->getImageUrl($id);
			
			$logger->log("Found ".$processor['name']." Cover ".$imageUrl."", PEAR_LOG_INFO);
			$result = processImageURL($imageUrl, $size);
			if($result)
			{
				$logger->log("Found ".$processor['name']." Cover ".$imageUrl." is valid", PEAR_LOG_INFO);
				exit();
			}
			$logger->log("The image was not valid", PEAR_LOG_INFO);
		}
		catch(DomainException $e)
		{
			$logger->log("OverDrive Cover Not Found ".$e->getMessage(), PEAR_LOG_INFO);
		}
	}
}

if (isset($configArray['Content']['coverimages'])) {
	$providers = explode(',', $configArray['Content']['coverimages']);
	foreach ($providers as $provider) {
		$logger->log("Checking provider $provider", PEAR_LOG_INFO);
		$provider = explode(':', $provider);
		$func = $provider[0];
		$key = isset($provider[1]) ? $provider[1] : '';
		if ($func($key)) {
			$logger->log("Found image from $provider[0]", PEAR_LOG_INFO);
			exit();
		}
	}
}



$logger->log("Image Not Found from Providers", PEAR_LOG_INFO);
$logger->log("Getting the generic using the ID: ".$id, PEAR_LOG_INFO);

dieWithFailImage($id);

function syndetics($clientId)
{
	global $logger;
	$syndeticsCovers = new SyndeticsCovers();
	$upc = (isset($_GET['upc']) ? $_GET['upc'] : NULL);
	$category = (isset($_GET['category']) ? $_GET['category'] : NULL);
	$size = (isset($_GET['size']) ? $_GET['size'] : NULL);
	$isbn = (isset($_GET['isn']) ? $_GET['isn'] : NULL);
	$imageUrl = $syndeticsCovers->getImageUrl($isbn, $upc, $size, $clientId, $category);	
	return processImageURL($imageUrl, $size);
}

function librarything()
{
	global $configArray;
	$libraryThingCovers = new LibraryThingCovers();
	try
	{
		$size = (isset($_GET['size']) ? $_GET['size'] : NULL);
		$isbn = (isset($_GET['isn']) ? $_GET['isn'] : NULL);
		$imageUrl = $libraryThingCovers->getImageUrl($configArray['LibraryThing']['devkey'], $isbn, $size);
	} catch (DomainException $e) {
		return false;
	}
	return processImageURL($imageUrl, $size);
}

function openlibrary()
{
	$openLibraryCovers = new OpenLibraryCovers();
	try 
	{
		$size = (isset($_GET['size']) ? $_GET['size'] : NULL);
		$isbn = (isset($_GET['isn']) ? $_GET['isn'] : NULL);
		$imageUrl = $openLibraryCovers->getImageUrl($isbn, $size);
	}
	catch (DomainException $e)
	{
		return false;
	}
	return processImageURL($imageUrl, $size);
}

function google()
{
	$googleCover = new GoogleBooksCovers();
	try
	{
		$size = (isset($_GET['size']) ? $_GET['size'] : NULL);
		$isbn = (isset($_GET['isn']) ? $_GET['isn'] : NULL);
		$imageUrl = $googleCover->getImageUrl($isbn, $size);
	} catch (DomainException $e) {
		return false;
	}

	return processImageURL($imageUrl, $size);
}

function lookForPublisherFile($folderToCheck, $isbn10, $isbn13){
	global $logger;
	if (!file_exists($folderToCheck)){
		$logger->log("No publisher directory, expected to find in $folderToCheck", PEAR_LOG_INFO);
		return;
	}
	//$logger->log("Looking in folder $folderToCheck for cover image supplied by publisher.", PEAR_LOG_INFO);
	//Check to see if the file exists in the folder
	$matchingFiles10 = glob($folderToCheck . $isbn10 . "*.jpg");
	$matchingFiles13 = glob($folderToCheck . $isbn13 . "*.jpg");
	if (count($matchingFiles10) > 0){
		//We found a match
		$logger->log("Found a publisher file by 10 digit ISBN " . $matchingFiles10[0], PEAR_LOG_INFO);
		return processImageURL($matchingFiles10[0], true);
	}elseif(count($matchingFiles13) > 0){
		//We found a match
		$logger->log("Found a publisher file by 13 digit ISBN " . $matchingFiles13[0], PEAR_LOG_INFO);
		return processImageURL($matchingFiles13[0], true);
	}else{
		//$logger->log("Did not find match by isbn 10 or isbn 13, checking sub folders", PEAR_LOG_INFO);
		//Check all subdirectories of the current folder
		$subDirectories = array();
		$dh = opendir($folderToCheck);
		if ($dh){
			while (($file = readdir($dh)) !== false) {

				if (is_dir($folderToCheck . $file) && $file != '.' && $file != '..'){
					//$logger->log("Found file $file", PEAR_LOG_INFO);
					$subDirectories[] = $folderToCheck . $file . '/';
				}
			}
			closedir($dh);
			foreach ($subDirectories as $subDir){
				//$logger->log("Looking in subfolder $subDir for cover image supplied by publisher.");
				if (lookForPublisherFile($subDir, $isbn10, $isbn13)){
					return true;
				}
			}
		}
	}
	return false;
}

/**
 * Display a "cover unavailable" graphic and terminate execution.
 */
function dieWithFailImage($id = NULL)
{
	global $configArray;
	
	$isEcontent = isEcontentBookCover();
	$size = (isset($_GET['size']) ? $_GET['size'] : NULL);
	
	switch ($size)
	{
		case "small":
		case "medium":
		case "large":
			$size = $size;
			break;
		default:
			$size = "large";
			break;
	}
	
	$title  = "";
	$author = "";
	$type   = "other";
	if ($id !== NULL)
	{
		if ($isEcontent)
		{
				
			$eContentRecord = new EContentRecord();
			$eContentRecord->id =$id;
			if ($eContentRecord->find(true))
			{
				$eContentRecord->fetch();
				$eContentRecordServices = new EContentRecordServices();
				$title=$eContentRecordServices->getMarcTitle($eContentRecord);
				$author=$eContentRecordServices->getMarcAuthor($eContentRecord);
				$type   = "emedia";
			}
		}
		else
		{
			$resource = new Resource();
			$resource->record_id = $id;
			if ($resource->find(true))
			{
				$resource->fetch();
				$title=$resource->title;
				$author=$resource->author;
				$type = CoversType::getCoverTypeFromFormat($resource->format_category);
			}
		}
	}

	$title=urlencode($title);
	$author=urlencode($author);
	$url = $configArray['Content']['urlDefaultImagesAPI']."?title=".$title."&author=".$author."&type=".$type;
	processImageURL($url, $size, false);
	exit();
}

/**
 * Load image from URL, store in cache if requested, display if possible.
 *
 * @param   $url        URL to load image from
 * @param   $cache      Boolean -- should we store in local cache?
 * @return  bool        True if image displayed, false on failure.
 */
function processImageURL($imageUrl, $size, $cache = true)
{
	
	global $localFile, $logger, $timer;
	$logger->log("Processing ".$imageUrl, PEAR_LOG_INFO);


	if ($image = @file_get_contents($imageUrl)) {
		$logger->log("Got image From ".$imageUrl, PEAR_LOG_INFO);
		// Figure out file paths -- $tempFile will be used to store the downloaded
		// image for analysis.  $finalFile will be used for long-term storage if
		// $cache is true or for temporary display purposes if $cache is false.
		$tempFile = str_replace('.jpg', uniqid(), $localFile);
		$finalFile = $cache ? $localFile : $tempFile . '.jpg';

		// If some services can't provide an image, they will serve a 1x1 blank
		// or give us invalid image data.  Let's analyze what came back before
		// proceeding.
		
		if (!@file_put_contents($tempFile, $image)) 
		{
			$logger->log("Unable to write to image directory $tempFile.", PEAR_LOG_ERR);
			die("Unable to write to image directory $tempFile.");
		}
		list($width, $height, $type) = @getimagesize($tempFile);
		$logger->log("Image ATTR. W:".$width." H".$height." Type:".$type, PEAR_LOG_INFO);
		// File too small -- delete it and report failure.
		if ($width < 10 && $height < 10) {
			$logger->log("Image From ".$imageUrl." too small", PEAR_LOG_INFO);
			@unlink($tempFile);
			return false;
		}

		if ($size == 'small'){
			$maxDimension = 100;
		}elseif ($size == 'medium'){
			$maxDimension = 200;
		}else{
			$maxDimension = 400;
		}

		//Check to see if the image needs to be resized
		if ($width > $maxDimension || $height > $maxDimension){
			$logger->log("The image needs to be resized", PEAR_LOG_INFO);
			
			// We no longer need the temp file:
			@unlink($tempFile);

			if ($width > $height){
				$new_width = $maxDimension;
				$new_height = floor( $height * ( $maxDimension / $width ) );
			}else{
				$new_height = $maxDimension;
				$new_width = floor( $width * ( $maxDimension / $height ) );
			}

			//$logger->log("Resizing image New Width: $new_width, New Height: $new_height", PEAR_LOG_INFO);

			// create a new temporary image
			$tmp_img = imagecreatetruecolor( $new_width, $new_height );

			$imageResource = imagecreatefromstring($image);
			// copy and resize old image into new image
			if (!imagecopyresampled( $tmp_img, $imageResource, 0, 0, 0, 0, $new_width, $new_height, $width, $height )){
				$logger->log("Could not resize image ".$imageUrl." to ".$localFile, PEAR_LOG_ERR);
				return false;
			}

			// save thumbnail into a file
			if (!@imagejpeg( $tmp_img, $finalFile, 90 )){
				$logger->log("Could not save resized file ".$localFile, PEAR_LOG_ERR);
				return false;
			}


		}else{
			$logger->log("Image is the correct size, not resizing.", PEAR_LOG_INFO);

			// Conversion needed -- do some normalization for non-JPEG images:
			if ($type != IMAGETYPE_JPEG) {
				
				$logger->log("The original image is not JPEG", PEAR_LOG_INFO);
				
				// We no longer need the temp file:
				@unlink($tempFile);

				// Try to create a GD image and rewrite as JPEG, fail if we can't:
				if (!($imageGD = @imagecreatefromstring($image))) {
					$logger->log("Could not create image from string ".$imageUrl, PEAR_LOG_ERR);
					return false;
				}
				if (!@imagejpeg($imageGD, $finalFile, 90)) {
					$logger->log("Could not save image to file ".$localFile, PEAR_LOG_ERR);
					return false;
				}
			} else {
				// If $tempFile is already a JPEG, let's store it in the cache.
				@rename($tempFile, $localFile);
			}
		}

		$logger->log("SetUp Header for JPEG Image ".$localFile, PEAR_LOG_INFO);
		// Display the image:
		header('Content-type: image/jpeg');
		//header('Content-type: text/plain'); //Use this to debug notices and warnings
		readfile($finalFile);

		// If we don't want to cache the image, delete it now that we're done.
		if (!$cache) {
			@unlink($finalFile);
		}
		$timer->logTime("Finished processing image url");

		return true;
	} else {
		$logger->log("Could not load the file as an image ".$imageUrl, PEAR_LOG_INFO);
		return false;
	}
}

function isEcontentBookCover()
{
	return ( (isset($_GET['econtent'])&&$_GET['econtent']=="true") ? true : false);
}
?>