<?php
require_once dirname(__FILE__).'/../../vufind/classes/Utils/EContentFormatType.php';
require_once dirname(__FILE__).'/../../vufind/classes/services/FreeEcontentRecordServices.php';

class FreeEcontentRecordServicesTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $eContentRecordMock;
	
	public function setUp()
	{
		$this->eContentRecordMock = $this->getMock("IEContentRecord");
		$this->service = new FreeEcontentRecordServices();
		parent::setUp();		
	}
	
	
	/**
	* method getFormatType
	* when unknowFormat
	* should returnUnknown
	*/
	public function test_getFormatType_unknowFormat_returnUnknown()
	{
		$expected = EContentFormatType::unknown;
		$this->prepareEContentMock("AdummyGenre");
		$actual = $this->service->getFormatType($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method getFormatType
	* when calledInfoGenre
	* should returnCorrectType
	* @dataProvider DP_getFormatType
	*/
	public function test_getFormatType_calledInfoGenre_returnCorrectType($genre, $expected)
	{
		
		$this->prepareEContentMock($genre);
		
		$actual = $this->service->getFormatType($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
		
	}

	public function DP_getFormatType()
	{
		return array(
				  		array("Downloadable Gutenberg ebooks.Humorous fiction.", EContentFormatType::eBook),
						array("Downloadable Gutenberg ebooks.Electronic books.", EContentFormatType::eBook),
						array("Downloadable Overdrive eaudio.Audiobooks.", EContentFormatType::eAudio),
						array("Downloadable Access Video on Demand evideo.Video", EContentFormatType::eVideo)
					);
	}
	
	/**
	 * method getFormatType
	 * when calledFormatISBNField
	 * should returnCorrectType
	 * @dataProvider DP_getFormatType_calledFormatISBNField
	 */
	public function test_getFormatType_calledFormatISBNField_returnCorrectType($isbn, $expected)
	{
		$this->prepareEContentMock(NULL, $isbn);
		$actual = $this->service->getFormatType($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	
	}
	
	public function DP_getFormatType_calledFormatISBNField()
	{
		return array(
				array("9780756670894 (electronic book)", EContentFormatType::eBook),
				array("9780062209528 (electronic bk.)0062209523 (electronic bk.)", EContentFormatType::eBook)
		);
	}
	
	/**
	* method getFormatType
	* when RecordIdFreegal
	* should return_eAudio
	*/
	public function test_getFormatType_RecordIdFreegal_return_eAudio()
	{
		$expected = EContentFormatType::eMusic;
		$this->prepareEContentMock(NULL, NULL, 'Freegal');
		$actual = $this->service->getFormatType($this->eContentRecordMock);
		$this->assertEquals($expected, $actual);
	}
	

	//Exercise
	private function prepareEContentMock($genre = NULL, $isbn = NULL, $source = NULL)
	{
		$this->eContentRecordMock->genre = $genre;
		$this->eContentRecordMock->isbn = $isbn;
		$this->eContentRecordMock->source = $source;
	}
	
	
}

?>