<?php
require_once dirname(__FILE__).'/../mother/marcRecordMother.php';
require_once dirname(__FILE__).'/../../vufind/web/sys/eContent/EContentRecord.php';


class EContentRecordTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	private $marcRecordMother;
	private $marcRecordFieldsMock;
	
	public function setUp()
	{
		$this->marcRecordFieldsMock = $this->getMock("IMarcRecordFields", array("getISSN", "getTitle", "getSeries",
																				"getISBN", "getPublicationPlace", "getPublisher",
																				"getYear", "getEdition", "GetEAN", "getSecondayAuthor", 
																				"getAuthor", "getSourceUrl"));
		
		$this->marcRecordMother = new MarcRecordMother();
		
		$this->service = new EContentRecord();
		parent::setUp();		
	}
	
	/**
	 * method getUniqueSystemID
	 * when called
	 * should returnsCorrectly
	 */
	public function test_getUniqueSystemID_called_returnsCorrectly()
	{
		$id = 12;
		$expected = "econtentRecord".$id;
		$this->service->id = $id;
		$actual = $this->service->getUniqueSystemID();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getPermanentPath
	 * when called
	 * should returnCorrectly
	 */
	public function test_getPermanentPath_called_returnCorrectly()
	{
		$id = 12;
		$this->service->id = $id;
		$expected = "/EcontentRecord/".$id;
		$actual = $this->service->getPermanentPath();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMarcString
	 * when called
	 * should returnCorrectly
	 */
	public function test_getMarcString_called_returnCorrectly()
	{
		$expected = "aDummyMarcStringValue";
		$this->service->marcRecord = $expected;
		$actual = $this->service->getMarcString();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getType
	 * when called
	 * should returnAlwaysRecord
	 */
	public function test_getType_called_returnAlwaysRecord()
	{
		$expected = "EcontentRecord";
		$actual = $this->service->getType();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getMarcRecordFieldReader
	 * when called
	 * should returnCorrectly
	 */
	public function test_getMarcRecordFieldReader_called_returnCorrectly()
	{
		$expected = "MarcRecordFields";
		$this->service->marcRecord = "";
		$actual = $this->service->getMarcRecordFieldReader();
		$this->assertEquals($expected, get_class($actual));
	}
	
	/**
	* method getNormalizedMarcRecord
	* when called
	* should returnMarcRecord
	*/
	public function test_getNormalizedMarcRecord_called_returnMarcRecord()
	{
		$expected = "03236nam a2200577Ii 4500001001300000003000600013005001700019006001900036007001500055008004100070020002600111035002100137040002300158049000900181082002000190092001000210099001300220100005400233245005500287246003300342264007600375264001100451264000900462300004700471336002600518337002600544338003600570490004400606511003100650518006500681520038100746545064501127588011901772600005201891610005201943610004601995650004102041650005202082650005702134655004502191655002502236655002202261700003602283710006302319710005502382710006402437830004502501856008802546950001202634994001202646ocn834617100OCoLC20130423113200.0m     o  d        cr |||||||||||130402t20132013coua    o     000 0deng d  a9781940103006 (ebook)  a(OCoLC)834617100  aDADbengcDADerda  aDADA04a355.0092273223  aeBook 9a2003.2081 aCaporiccio, Attilio F.,d1916-2007,einterviewee.10aOral history interview with Attilio F. Caporiccio.3 aAttilio Caporiccio interview 1a[Castle Rock, Colo.] :bDouglas County History Research Center,c[2013] 4c©2013 0c2004  a1 online resource (42 p.) :billustrations  atextbtxt2rdacontent  acomputerbc2rdamedia  aonline resourcebcr2rdacarrier1 aVeterans History Project oral histories0 aInterviewer: Barbara Belt.  aInterview conducted on September 14, 2004, Denver, Colorado.  aIn this oral history transcript, Caporiccio describes his experiences trying to enlist in the Civilian Conservation Corps and the Navy; life on Hawaii leading up to and including the attack on Pearl Harbor; flying patrol and combat missions over the Pacific; experiences providing support to U.S. Marines on Midway, and the British on Fiji; and being stationed on Guadalcanal.  aAttilio F. Caporiccio was born in Philadelphia, Pennsylvania, on March 17, 1916. He enlisted in the Army Air Corps on July 5, 1939 and served until October 9, 1945; he re-enlisted September 30, 1948, and retired from service with the rank of master sergeant on March 20, 1953. Mr. Caporiccio served in the Army Air Corps, the U.S. Army Air Force, and the U.S. Air Force during World War II and the Korean War, and was present at Pearl Harbor on December 7, 1941. He is a charter member of Hickam Field; served as an armorer, gunnery instructor, and B-17 turret operator with the 42nd Bomb Squadron; and participated in the Pacific campaign.  aDescription based on online resource; title from EPUB title page (Douglas County Libraries, viewed April 1, 2013).10aCaporiccio, Attilio F.,d1916-2007vInterviews.10aUnited States.bArmy Air ForcesxMilitary life.10aUnited States.bAir ForcexMilitary life. 0aVeteranszUnited StatesvInterviews. 0aVeteranszColoradozDouglas CountyvInterviews. 0aWorld War, 1939-1945vPersonal narratives, American. 4aDownloadable Douglas County e-documents. 7aOral histories.2aat 4aElectronic books.1 aBelt, Barbara A.,einterviewer.2 aDouglas County Veterans History Project,esponsoring body.2 aVeterans History Project (U.S.),esponsoring body.2 aDouglas County History Research Center (Colo.),epublisher. 0aVeterans History Project oral histories.40uhttp://douglascountylibraries.org/files/nodrmbooks/9781940103006.epubyAccess eBook  a1143831  aC0bDAD";
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
		$expected = "http://douglascountylibraries.org/files/nodrmbooks/9781940103006.epub";
		
		$marcRecord = $this->marcRecordMother->getMarcRecord();
		$this->service->marcRecord = $marcRecord;
		$this->service->sourceUrl = "";
		
		$actual = $this->service->getsourceurl();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method isBriefRecord 
	* when ItIsNot
	* should returnFalse
	*/
	public function test_isBriefRecord_ItIsNot_returnFalse()
	{
		$this->service->reviewStatus = "aDummyReviewStatusValue";
		$actual = $this->service->isBriefRecord();
		$this->assertFalse($actual);
	}
	
	/**
	 * method isBriefRecord
	 * when ItIs
	 * should returnTrue
	 */
	public function test_isBriefRecord_ItIs_returnTrue()
	{
		$this->service->reviewStatus = EContentRecord::briefRecord;
		$actual = $this->service->isBriefRecord();
		$this->assertTrue($actual);
	}
	
	
	
	/**
	 * method isFree
	 * when recordIsNotFree
	 * should returnFalse
	 * @dataProvider DP_isFree_recordIsNotFree
	 */
	public function test_isFree_recordIsNotFree_returnFalse($accessType)
	{
		$this->service->accessType = $accessType;
		$actual = $this->service->isFree();
		$this->assertFalse($actual);
	}
	
	public function DP_isFree_recordIsNotFree()
	{
		return array(
				array("acs")
		);
	}
	
	/**
	 * method isFree
	 * when recordIsFree
	 * should returnTrue
	 * @dataProvider DP_isFree_recordIsFree
	 */
	public function test_isFree_recordIsFree_returnTrue($accessType)
	{
		$this->service->accessType = $accessType;
		$actual = $this->service->isFree();
		$this->assertTrue($actual);
	}
	
	public function DP_isFree_recordIsFree()
	{
		return array(
				array("free"),
				array("singleUse")
		);
	}
	
	/**
	* method getEAN 
	* when called
	* should returnAlwaysEmptyString
	*/
	public function test_getEAN_called_returnAlwaysEmptyString()
	{
		$expected = "";
		$actual = $this->service->getEAN();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method getSecondaryAuthor 
	* when called
	* should returnCorrectly
	*/
	public function test_getSecondaryAuthor_called_returnCorrectly()
	{
		$expected = "aDummySecondaryAuthor";
		$this->service->author2 = $expected;
		$actual = $this->service->getSecondaryAuthor();
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getTitle
	 * when mysqlTitleIsEmpty
	 * should executesCorrectly
	 */
	public function test_getTitle_mysqlTitleIsEmpty_executesCorrectly()
	{
		$expected = "aDummyTitle";
		$this->prepareMarcRecordFieldCallMethod("getTitle", $expected);
	
		$actual = $this->service->getTitle($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	 * method getAuthor
	 * when mysqlAuthorIsEmpty
	 * should executesCorrectly
	 */
	public function test_getAuthor_mysqlAuthorIsEmpty_executesCorrectly()
	{
		$expected = "aDummyAuthor";
		$this->prepareMarcRecordFieldCallMethod("getAuthor", $expected);
	
		$actual = $this->service->getAuthor($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getSourceUrl
	 * when sourceUrlMysqlEmpty
	 * should executesCorrectly
	 */
	public function test_getSourceUrl_sourceUrlMysqlEmpty_executesCorrectly()
	{
		$expected = "aDummySourceUrl";
		$this->prepareMarcRecordFieldCallMethod("getSourceUrl", $expected);

		$actual = $this->service->getSourceUrl($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getYear
	 * when alwaysGoToMarc
	 * should executesCorrectly
	 */
	public function test_getYear_alwaysGoToMarc_executesCorrectly()
	{
		$expected = "aDummyYear";
		$this->prepareMarcRecordFieldCallMethod("getYear", $expected);
	
		$actual = $this->service->getYear($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * method getPublicationPlace
	 * when alwaysGoToMarc
	 * should executesCorrectly
	 */
	public function test_getPublicationPlace_alwaysGoToMarc_executesCorrectly()
	{
		$expected = "aDummyPublicationPlace";
		$this->prepareMarcRecordFieldCallMethod("getPublicationPlace", $expected);
	
		$actual = $this->service->getPublicationPlace($this->marcRecordFieldsMock);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	* method isACS 
	* when itIsNot
	* should returnFalse
	*/
	public function test_isACS_itIsNot_returnFalse()
	{
		$this->service->accessType = "aNonValidACSType";
		$actual = $this->service->isACS();
		$this->assertFalse($actual);
	}
	
	/**
	 * method isACS
	 * when itIs
	 * should returnTrue
	 */
	public function test_isACS_itIs_returnTrue()
	{
		$this->service->accessType = EcontentRecord::accesType_acs;
		$actual = $this->service->isACS();
		$this->assertTrue($actual);
	}
	
	/**
	* method getShelfMark 
	* when called
	* should returnAlwaysEmpty
	*/
	public function test_getShelfMark_called_returnAlwaysEmpty()
	{
		$expected = "";
		$actual = $this->service->getShelfMark();
		$this->assertEquals($expected, $actual);
	}
	
		
	
	//Prepares
	private function prepareGetTitleAuthor()
	{
		$this->service->title = "a Dummy Title from Database";
		$this->service->author = "a Dummy Author From Database";
	}

	private function prepareMarcRecordFieldCallMethod($method, $result)
	{
		$this->marcRecordFieldsMock->expects($this->once())
									->method($method)
									->will($this->returnValue($result));
	}
}
?>