<?php
require_once dirname(__FILE__).'/FileMarc.php';
require_once dirname(__FILE__).'/MarcSubField.php';

interface IMarcRecordFields{}

class MarcSubFieldNonValidMarcString
{
	public function __call($method, $args)
	{
		return "";
	}
}

class MarcRecordFields implements IMarcRecordFields
{
	const typeRecord = "Record";
	const typeEcontentRecord = "EcontentRecord";
	
	private $marcSubfield;
	private $record;
	
	public function __construct(IMarcRecordFieldsReader $record, IMarcSubfield $marcSubField = NULL)
	{
		$this->record = $record;
		if(!$marcSubField)
		{
			$this->setMarcString($this->record->getMarcString());
		}
		else
		{
			$this->marcSubfield = $marcSubField;
		}
		
		
	}
	
	public function getSourceUrl()
	{
		if($this->record->getType() == self::typeRecord)
		{
			return "";
		}
		return $this->marcSubfield->getCode("856", "u");
	}
	
	private function setMarcString($marcString)
	{
		$marcString = $this->cleanMarcString($marcString);
		$fileMarc = new FileMarc($marcString, File_MARC::SOURCE_STRING);
		
		$fileMarcRecord = $fileMarc->next();
		if($fileMarcRecord !== false)
		{
			$this->marcSubfield = new MarcSubField($fileMarcRecord);
		}
		else
		{
			$this->marcSubfield = new MarcSubFieldNonValidMarcString();
		}
	}
	
	public function getISSN()
	{
		return $this->marcSubfield->getCode("022", "a");
	}
	
	public function getISBN()
	{
		$dirtyISBN = $this->marcSubfield->getCode("020", "a");
		preg_match("/\\d{12}[X\\d]/", $dirtyISBN, $matches);
		if(isset($matches[0]))
		{
			return $matches[0];
		}
		return '';
	}
	
	public function getTitle()
	{
		return $this->marcSubfield->getCode("245", "a");
	}
	
	public function getSeries()
	{
		$primaryFields = array(
				'440' => array('a', 'p'),
				'800' => array('a', 'b', 'c', 'd', 'f', 'p', 'q', 't'),
				'830' => array('a', 'p'));

		$series = array();
		foreach($primaryFields as $key=>$val)
		{
			foreach($primaryFields[$key] as $code)
			{
				$value = $this->marcSubfield->getCode($key, $code);
				if(!empty($value))
				{
					$series[] = $value;
				}
			}
		}
		return $series;
	}
	
	public function getPublicationPlace()
	{
		return $this->marcSubfield->getCode("260", "a");
	}
	
	public function getPublisher()
	{
		return $this->marcSubfield->getCode("260", "b");
	}
	
	public function getEdition()
	{
		return $this->marcSubfield->getCode("250", "a");
	}
	
	public function getAuthor()
	{
		return $this->marcSubfield->getCode("100", "a");
	}
	
	public function getYear()
	{
		$dirtyYear = $this->marcSubfield->getCode("260", "c");
		preg_match("/\\d{4}/", $dirtyYear, $matches);
		if(isset($matches[0]))
		{
			return $matches[0];
		}
		return '';
	}
	
	public function getShelfMark()
	{
		$firstPart = $this->marcSubfield->getCode("092", "a");
		$secondPart = $this->marcSubfield->getCode("092", "b");
		return $firstPart." ".$secondPart;
	}
	
	
	/******************************************************
	 ***                  PRIVATES                      ***
	 ******************************************************/
	private function cleanMarcString($marcString)
	{
		$marcString = preg_replace('/#29;/', "\x1D",  $marcString);
		$marcString = preg_replace('/#30;/', "\x1E",  $marcString);
		$marcString = preg_replace('/#31;/', "\x1F",  $marcString);
		$marcString = preg_replace('/#163;/', "\xA3", $marcString);
		$marcString = preg_replace('/#169;/', "\xA9", $marcString);
		$marcString = preg_replace('/#174;/', "\xAE", $marcString);
		$marcString = preg_replace('/#230;/', "\xE6", $marcString);
		return $marcString;
	}
	
	/*
	 * Test Purpouse 
	 */
	public function getMarcSubField() 
	{
		return $this->marcSubfield;
	}
}
?>