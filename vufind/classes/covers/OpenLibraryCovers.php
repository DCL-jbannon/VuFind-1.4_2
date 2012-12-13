<?php


class OpenLibraryCovers {
	
	public function getImageUrl($isbn, $size) {

		if (empty($isbn))
		{
			throw new DomainException("OpenLibraryCovers::getImageUrl The ISBN cannot be empty");
		}
		
		switch($size) {
			case 'large':
				$size = 'L';
				break;
			case 'medium':
				$size = 'M';
				break;
			case 'small':
			default:
				$size = 'S';
				break;
		}
		
		// Retrieve the image; the default=false parameter indicates that we want a 404 if the ISBN is not supported.
		$url = "http://covers.openlibrary.org/b/isbn/".$isbn."-".$size.".jpg?default=false";
		return $url;
	}
	
}

?>