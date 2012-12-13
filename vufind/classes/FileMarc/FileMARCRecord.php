<?php
require_once 'File/MARC/Record.php';

interface IFileMARCRecord{}

class FileMARCRecord extends File_MARC_Record implements IFileMARCRecord{
	
	public function __construct($source = null)
	{
		parent::__construct($source);
	}
	
	
	public static function getFileMarcRecord($args)
	{

		if(!isset($args['marc']) && isset($args['marcxml']))
		{
			throw new InvalidArgumentException("You must provide either marc or marcxml for the record");
		}
		
		$object = new FileMARCRecord();
		$object->setLeader($args['leader']);
		$object->setFields($args['fields']);
		$object->setWarnings($args['warnings']);
		$object->setMarc((isset($args['marc']) ? $args['marc'] : ""));
		$object->setMarcXml((isset($args['marcxml']) ? $args['marcxml'] : ""));
		return $object;
	}
	
	private function __clone(){}
	
	//Setters and getters
	private function setFields($fields)
	{
		$this->fields = $fields;
	}
	
	private function setWarnings($warnings)
	{
		$this->warnings = $warnings;
	}
	
	private function setMarcXml($marcxml)
	{
		$this->marcxml = $marcxml;
	}
	
	private function setMarc($marc)
	{
		$this->marc = $marc;
	}
	
}
?>