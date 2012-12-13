<?php

/**
 * All the methods return a json_decode result!
 * @author jgimenez
 */

interface IGoogleBooksAPIWrapper{}

class GoogleBooksAPIWrapper implements IGoogleBooksAPIWrapper {
	
	
	public function getBookInfo($isbn)
	{
		$url = 'http://books.google.com/books?jscmd=viewapi&bibkeys='.$isbn;
		$result = file_get_contents($url);
		$result = preg_replace("/var _GBSBookInfo \= /", "", $result);
		$result = preg_replace("/\;$/", "", $result);
		return json_decode($result);
	}
	
	
	
}

?>