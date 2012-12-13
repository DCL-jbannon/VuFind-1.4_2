<?php
require_once dirname(__FILE__).'/../classes/covers/TitleNoCovers.php';

$service = new TitleNoCovers();
$result = $service->getCover($_GET['title'], $_GET['author'], $_GET['type']);

header('Content-Type: image/png');
imagepng($result['resource']);

?>