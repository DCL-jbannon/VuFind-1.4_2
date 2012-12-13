<?php

interface IFreegalServices{}

class FreegalServices implements IFreegalServices{
	
	function getAlbumId(IEContentRecord $eContentRecord)
	{
		$items = $eContentRecord->getItems();
		if(!empty($items))
		{
			$linkParts = explode("/",$items[0]->link);
			return $linkParts[9];
		}
		return false;
	}
}

?>