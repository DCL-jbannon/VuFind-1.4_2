<?php

class GoogleAPIResultsMother {
	
	const isbn = "9780345529411";
	
	function getBookInfoIBSN($isbn = NULL) {
		
		if(!$isbn) $isbn = self::isbn;
		
		$object = new stdClass();
		$object->$isbn = new stdClass();
		$object->$isbn->bib_key = $isbn;
		$object->$isbn->info_url = "http://books.google.com/books?id=niMxoYVApysC&ie=ISO-8859-1&source=gbs_ViewAPI";
		$object->$isbn->preview_url = "http://books.google.com/books?id=niMxoYVApysC&printsec=frontcover&ie=ISO-8859-1&source=gbs_ViewAPI";
		$object->$isbn->thumbnail_url = "http://bks8.books.google.com/books?id=niMxoYVApysC&printsec=frontcover&img=1&zoom=5&edge=curl";
		$object->$isbn->embeddable = 1;
		$object->$isbn->can_download_pdf = "";
		$object->$isbn->is_pdf_drm_enabled = "";
		$object->$isbn->is_epub_drm_enabled = "";
		return $object;
		
	}
	
	function getBookInfoIBSNWithNoThumbnailURl($isbn = NULL) {
	
		$object = $this->getBookInfoIBSN($isbn);
		unset($object->$isbn->thumbnail_url);
		return $object;
	
	}
	
	function getBookInfoIBSNNotFound() {
		$object = new stdClass();
		return $object;
	}
	
}


?>