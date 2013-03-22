<?php
interface IGenericRecord
{
	public function getPermanentPath();
	public function getUniqueSystemID();
	public function getISSN();
	public function getISBN();
	public function getAuthor();
	public function getTitle();
	public function getEAN();
	public function getSecondaryAuthor();
	public function getPublisher();
	public function getSeries();
	public function getPublicationPlace();
	public function getYear();
	public function getEdition();
	public function getShelfMark();
}
?>