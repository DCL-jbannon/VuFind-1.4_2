<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/EContentFormatType.php';
require_once dirname(__FILE__).'/../../vufind/classes/services/OverDriveServices.php';

class OverDriveServicesTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $eContentRecordMock;
	
	private $fileMarcMock;
	private $fileMarcRecordMock;
	private $marcSubfieldMock;
	private $regularExpressionsMock;
	
	public function setUp()
	{
		$this->eContentRecordMock = $this->getMock("IEContentRecord");
		$this->fileMarcMock = $this->getMock("IFileMarc",array("next"));
		$this->fileMarcRecordMock = $this->getMock("IFileMARCRecord");
		$this->marcSubfieldMock = $this->getMock("IMarcSubfieldMock", array("getCode"));
		$this->regularExpressionsMock = $this->getMock("IRegularExpressions", array("getFieldValueFromURL"));
		
		$this->service = new OverDriveServices($this->regularExpressionsMock);
		parent::setUp();		
	}
	
	
	/**
	 * method getFormatType
	 * when calledWithNonCorrectGenre
	 * should returnUnknow
	 */
	public function test_getFormat_calledWithNonCorrectGenre_returnUnknow()
	{
		$expected = EContentFormatType::unknown;
		$this->eContentRecordMock->genre = "aDummyStringNonValidForOverDrive";
		$actual = $this->service->getFormatType($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method getFormatType
	* when called
	* should returnCorrectly
	* @dataProvider DP_getFormat
	*/
	public function test_getFormat_called_returnCorrectly($genre, $expected)
	{
		$this->eContentRecordMock->genre = $genre;
		$actual = $this->service->getFormatType($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getFormat()
	{
		return array(
				  		array("Downloadable Overdrive eaudio.Audiobooks.", EContentFormatType::eAudio),
						array("Downloadable Overdrive ebooks.Love stories.His....", EContentFormatType::eBook),
						array("Downloadable Overdrive evideo.Comedy films.Fea...", EContentFormatType::eVideo),
						array("eBook", EContentFormatType::eBook),
						array("Audiobook", EContentFormatType::eAudio),
						array("Video", EContentFormatType::eVideo)
					);
	}
	
	/**
	* method getOverDriveIdFromMarcRecord
	* when calledWithMarcRecordAsString
	* should returnCorrectValue
	*/
	public function test_getOverDriveIdFromMarcRecord_calledWithMarcRecordAsString_returnCorrectValue()
	{
		$fileMarcRecord = "aDummyFileMarcRecord";
		$expected = "aDummyOverDriveID";
		$overDriveURL = "aDummyOverDriveUrlContainsOverDriveID";
		$this->fileMarcMock->expects($this->once())
								 ->method("next")
								 ->will($this->returnValue($this->fileMarcRecordMock));
		$this->marcSubfieldMock->expects($this->once())
							   ->method("getCode")
							   ->with($this->equalTo("856"), $this->equalTo("u"), $this->equalTo("1"), $this->equalTo("1"))
							   ->will($this->returnValue($overDriveURL));
		$this->regularExpressionsMock->expects($this->once())
									 ->method("getFieldValueFromURL")
									 ->with($this->equalTo($overDriveURL), $this->equalTo("ID"))
									 ->will($this->returnValue($expected));
		$actual = $this->service->getOverDriveIdFromMarcRecord($fileMarcRecord, $this->fileMarcMock, $this->marcSubfieldMock);
		$this->assertEquals($expected, $actual);
	}
	
}
?>