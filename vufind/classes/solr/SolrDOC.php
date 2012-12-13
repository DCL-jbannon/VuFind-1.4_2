<?php

interface ISolrDOC{}

class SolrDOC implements ISolrDOC {
	
	private $id;
	private $author;
	private $description;
	private $origin;
	private $mysqlId;
	private $title;
	private $issn;
	private $isbn;
	private $publishdate;
	
	public function set(stdClass $doc)
	{
		$this->setId($doc->id);
		$this->setAuthor($doc->author);
		$this->setDescription($doc->description);
		$this->setOrigin($doc->origin);
		$this->setMysqlId($doc->mysqlid);
		$this->setTitle($doc->title[0]);
		$this->setISSN($doc->issn[0]);
		$this->setISBN($doc->isbn[0]);
		$this->setPublishDate($doc->publishDate[0]);
	}
	
	
	public function getId(){ return $this->id; }
	public function getAuthor(){ return $this->author; }
	public function getDescription(){ return $this->description; }
	public function getOrigin(){ return $this->origin; }
	public function getMysqlId(){ return $this->mysqlId; }
	public function getTitle(){ return $this->title; }
	public function getISSN(){ return $this->issn; }
	public function getISBN(){ return $this->isbn; }
	public function getPublishDate(){ return $this->publishdate; }
	
	private function setId($id)
	{
		$this->id = $id;
	}
	private function setAuthor($author)
	{
		$this->author = $author;
	}
	private function setDescription($description)
	{
		$this->description = $description;
	}
	private function setOrigin($origin)
	{
		$this->origin = $origin;
	}
	private function setMysqlId($mysqlId)
	{
		$this->mysqlId = $mysqlId;
	}
	private function setTitle($title)
	{
		$this->title = $title;
	}
	private function setISSN($issn)
	{
		$this->issn = $issn;
	}
	private function setISBN($isbn)
	{
		$this->isbn = $isbn;
	}
	private function setPublishDate($publishDate)
	{
		$this->publishdate = $publishDate;
	}
	
}

?>