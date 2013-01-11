<?php
require_once dirname(__FILE__).'/../mother/marcRecordMother.php';
require_once dirname(__FILE__).'/../../vufind/web/sys/eContent/EContentRecord.php';

class EContentRecordTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $marcRecordMother;
	
	public function setUp()
	{
		$this->marcRecordMother = new MarcRecordMother();
		
		$this->service = new EContentRecord();
		parent::setUp();		
	}
	
	
	/**
	* method getNormalizedMarcRecord
	* when called
	* should returnMarcRecord
	*/
	public function test_getNormalizedMarcRecord_called_returnMarcRecord()
	{
		$expected = "02481cim a2200529Ka 4450001001300000003000600013005001700019006001900036007001500055007001500070008004100085020004500126020001500171020001800186035002000204037004600224040002900270049000900299050002600308082001400334092001100348100002100359245008600380260004400466306001100510500002900521500003800550500001600588500002300604520068600627538006001313538003601373590001301409650002801422650003101450650003301481650004401514655003501558655002501593655002301618700002501641856012001666856010901786948001901895950002501914994001201939ocm75288117 OCoLC20120120194300.0m        d        sz usnnnn|||edcr nna||||||||061107s2006    nyunnn js      f  n eng d  c(sound recording : OverDrive Audio Book)  a0739345168  a9780739345160  a(OCoLC)75288117  bOverDrive, Inc.nhttp://www.overdrive.com  aTEFODcTEFODdTEFODdDAD  aDADA14aPZ7.D5455bMir 2006ab04a[Fic]222  aeAudio1 aDiCamillo, Kate.14aThe miraculous journey of Edward Tulaneh[electronic resource] /cKate DiCamillo.  a[New York] :bListening Library,c2006.  a015619  aDownloadable audio file.  aTitle from: Title details screen.  aUnabridged.  aDuration: 1:56:19.  aOnce, in a house on Egypt Street, there lived a china rabbit named Edward Tulane. The rabbit was very pleased with himself, and for good reason: he was owned by a girl named Abilene, who treated him with the utmost care and adored him completely. And then, one day, he was lost. Kate DiCamillo and Bagram Ibatoulline take us on an extraordinary journey, from the depths of the ocean to the net of a fisherman, from the top of a garbage heap to the fireside of a hobbies' camp, from the bedside of an ailing child to the streets of Memphis. And along the way, we are shown a true miracle -- that even a heart of the most breakable kind can learn to love, to lose, and to love again.  aRequires OverDrive Media Console (file size: 27865 KB).  aMode of access: World Wide Web.  acat49rev 0aToysvJuvenile fiction. 0aRabbitsvJuvenile fiction. 0aListeningvJuvenile fiction. 0aAdventure storiesvJuvenile literature. 4aDownloadable Overdrive eaudio. 7aLove stories.2gsafd 7aAudiobooks.2lcgft1 aIvey, Judith,d1951-40uhttp://www.emedia2go.org/ContentDetails.htm?ID=0EA036C9-C4DB-4A87-A741-5CC03BF9D96EyClick here to Download e-Audio4 yExcerptuhttp://excerpts.contentreserve.com/FormatType-25/1191-1/105292-TheMiraculousJourneyOfEdward.wma  aLTI 01/12/2012  a850055bHorizon bib#  aC0bDAD#29;";
		$stringMarcRecordFromDatabase = $this->marcRecordMother->getMarcRecord();
		
		$this->service->marcRecord = $stringMarcRecordFromDatabase;
		$actual = $this->service->getNormalizedMarcRecord($stringMarcRecordFromDatabase);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method isGutenberg
	* when isGutenberItem
	* should returnTrue
	*/
	public function test_isGutenberg_isGutenberItem_returnTrue()
	{
		$this->service->source = "Gutenberg";
		$actual = $this->service->isGutenberg();
		$this->assertTrue($actual);		
	}
	
	/**
	 * method isGutenberg
	 * when isNotGutenberItem
	 * should returnFalse
	 */
	public function test_isGutenberg_isNotGutenberItem_returnFalse()
	{
		$this->service->source = "aNonDummyGutenbergRecord";
		$actual = $this->service->isGutenberg();
		$this->assertFalse($actual);
	}
	
	/**
	* method isFreegal 
	* when isFregalItem
	* should returnTrue
	*/
	public function test_isFreegal_isFregalItem_returnTrue()
	{
		$this->service->source = "Freegal";
		$actual = $this->service->isFreegal();
		$this->assertTrue($actual);
	}
	
	/**
	 * method isFreegal
	 * when isNotFregalItem
	 * should returnFalse
	 */
	public function test_isFreegal_isNotFregalItem_returnFalse()
	{
		$this->service->source = "aNonDummyFreegalRecord";
		$actual = $this->service->isFreegal();
		$this->assertFalse($actual);
	}
	
	/**
	* method isOverDrive 
	* when isNotOverDrive
	* should returnFalse
	*/
	public function test_isOverDrive_isNotOverDrive_returnFalse()
	{
		$this->service->source = "aNonDummyOverDriveRecord";
		$actual = $this->service->isOverDrive();
		$this->assertFalse($actual);
	}
	
	/**
	 * method isOverDrive
	 * when isOverDrive
	 * should returnFalse
	 * @dataProvider DP_isOverDrive
	 */
	public function test_isOverDrive_isOverDrive_returnFalse($source)
	{
		$this->service->source = $source;
		$actual = $this->service->isOverDrive();
		$this->assertTrue($actual);
	}
	
	public function DP_isOverDrive()
	{
		return array(
					array("OverDrive"),
					array("OverDriveAPI")
				);
	}
		
	/**
	* method hasMarcRecord 
	* when itHasNotBySource
	* should returnfalse
	* @dataProvider DP_hasMarcRecordAttached_itHasNot
	*/
	public function test_hasMarcRecord_itHasNotBySource_returnfalse($source)
	{
		$this->service->source = $source;
		$actual = $this->service->hasMarcRecord();
		$this->assertFalse($actual);
	}
	
	public function DP_hasMarcRecordAttached_itHasNot()
	{
		return array(
				array("Freegal"),
				array("OverDriveAPI")
		);
	}
	
	/**
	 * method hasMarcRecord
	 * when marcRecordFieldIsNotValid
	 * should returnfalse
	 * @dataProvider DP_hasMarcRecordAttached_marcRecordFieldIsNotValid_returnfalse
	 */
	public function test_hasMarcRecord_itHasNot_returnfalse($contentMarcRecordField)
	{
		$this->service->marcRecord = $contentMarcRecordField;
		$actual = $this->service->hasMarcRecord();
		$this->assertFalse($actual);
	}
	
	public function DP_hasMarcRecordAttached_marcRecordFieldIsNotValid_returnfalse()
	{
		return array(
				array(""),
				array(NULL)
		);
	}
		
	/**
	 * method hasMarcRecord
	 * when itHas
	 * should returnfalse
	 */
	public function test_hasMarcRecord_itHas_returnfalse()
	{
		$this->service->source = "aDummyRecordShouldHaveMarcRecord";
		$this->service->marcRecord = "ADummyCoontentMarcRecord";
		$actual = $this->service->hasMarcRecord();
		$this->assertTrue($actual);
	}
	
	/**
	* method is3M 
	* when isNot3M
	* should returnFalse
	*/
	public function test_is3M_isNot3M_returnFalse()
	{
		$this->service->source = "aDummyNon3MEcontent";
		$actual = $this->service->is3M();
		$this->assertFalse($actual);
	}
	
	/**
	 * method is3M
	 * when is3M
	 * should returnTrue
	 */
	public function test_is3M_is3M_returnTrue()
	{
		$this->service->source = "3M";
		$actual = $this->service->is3M();
		$this->assertTrue($actual);
	}	
	

	/**
	* method getSourceUrl 
	* when sourceUrlFieldIsNotEmpty
	* should returnCorrectly
	*/
	public function test_getSourceUrl_sourceUrlFieldIsNotEmpty_returnCorrectly()
	{
		$expected = "aDummySourcelUrl";
		$this->service->sourceUrl = $expected;
		$actual = $this->service->getsourceurl();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getSourceUrl
	 * when sourceUrlFieldIsEmpty
	 * should getTheValueFromMarcRecord
	 */
	public function test_getSourceUrl_sourceUrlFieldIsEmpty_getTheValueFromMarcRecord()
	{
		$expected = "http://www.emedia2go.org/ContentDetails.htm?ID=0EA036C9-C4DB-4A87-A741-5CC03BF9D96E";
		
		$marcRecord = $this->marcRecordMother->getMarcRecord();
		$this->service->marcRecord = $marcRecord;
		$this->service->sourceUrl = "";
		
		$actual = $this->service->getsourceurl();
		$this->assertEquals($expected, $actual);
	}
	
		
		

}
?>