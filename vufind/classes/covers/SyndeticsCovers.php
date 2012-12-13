<?php

class SyndeticsCovers {
	
	const syndeticsURL = "http://syndetics.com";
	
	public function getImageUrl($isbn, $upc, $size, $clientId, $category = NULL)
	{

		switch ($size) {
			case 'small':
				$size = 'SC.GIF';
				break;
			case 'medium':
				$size = 'MC.GIF';
				break;
			case 'large':
			default:
				$size = 'LC.JPG';
				break;
		}
		
		if($category == "Books")
		{
			$imageUrl = self::syndeticsURL."/index.aspx?type=xw12&isbn=".$isbn."/{$size}&client=".$clientId;
		}
		else
		{
			$imageUrl = self::syndeticsURL."/index.aspx?type=xw12&isbn=".$isbn."/".$size."&client=".$clientId."&upc=" .$upc;
		}
		return $imageUrl;
	}
	
}

?>