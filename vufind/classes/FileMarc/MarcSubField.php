<?php

interface IMarcSubfieldMock{}

class MarcSubField implements IMarcSubfieldMock
{
	const indicatorsIndex = 'indicators';
	const codesIndex = 'codes';
	
	private $fileMarcRecord;
	private $structure;
	
	public function __construct(IFileMARCRecord $fileMarcRecord)
	{
		$this->fileMarcRecord = $fileMarcRecord;
	}
	
	public function getIndicator($subfieldNumber, $ind = 1, $numberOfSubfieldNumber = 1)
	{
		$fileMarcList = $this->fileMarcRecord->getFields($subfieldNumber);
		if (!empty($fileMarcList))
		{
			$i = 1;
			foreach($fileMarcList as $fileMarcField)
			{
				/* @var $fileMarcField File_MARC_Data_Field */
				if ($i == $numberOfSubfieldNumber)
				{
					$indicator = $fileMarcField->getIndicator($ind);
					if( ($indicator !== false) && ($indicator !== " ") )
					{
						return $indicator;
					}
					else
					{
						return '';
					}
				}
				$i++;
			}
		}
		return '';
	}
	
	public function getCode($subFieldNumber, $code, $subfieldPosition = 1, $codePosition = 1)
	{
		$fileMarcList = $this->fileMarcRecord->getFields($subFieldNumber);
		if (!empty($fileMarcList))
		{
			$i = 1;
			foreach($fileMarcList as $fileMarcField)
			{
				if ($i == $subfieldPosition)
				{
					/* @var $fileMarcField File_MARC_Data_Field */
					$subFields = $fileMarcField->getSubfields();
					if(!empty($subFields))
					{
						$j = 1;
						foreach ($subFields as $subField)
						{
							/* @var $subField File_MARC_Subfield */
							$currentCode = $subField->getCode();
							if ($currentCode == $code)
							{
								if ($j  == $codePosition)
								{
									return $subField->getData();
								}
								$j++;
							}
							
						}
					}
				}
				$i++;
			}
		}
		return '';		
	}
}
?>