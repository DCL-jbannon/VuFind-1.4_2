<?php

class LibraryThingCovers {
	
	public function getImageUrl($apiKey, $isbn, $size)
	{
		
		if(empty($isbn))
		{
			throw new DomainException("LibraryThingCovers::getImageUrl The ISBN cannot be empty");
		}
		
		switch($size) {
			case 'large':
			case 'medium':
			case 'small':
				break;
			default:
				$size = 'large';
				break;
		}
		return 'http://covers.librarything.com/devkey/'.$apiKey.'/'.$size.'/isbn/'.$isbn;
	}
}

?>