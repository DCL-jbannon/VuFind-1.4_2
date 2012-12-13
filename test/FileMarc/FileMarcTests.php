<?php

require_once dirname(__FILE__).'/../../vufind/classes/FileMarc/FileMarc.php';

class FileMarcTests extends PHPUnit_Framework_TestCase
{
	private $stringMarcRecord = "02419nam a2200421Ka 4450001001300000003000600013005001700019006001900036007001500055008004100070020006000111020005700171020005500228020005200283035002100335037004600356040002200402049000900424050002800433082001500461092001000476100002100486245009400507260003600601300002100637520078100658533020401439650002301643650002401666655003501690655002501725655002901750776004301779856011901822948001901941950002501960994001201985#30;ocn606205999#30;OCoLC#30;20120120202700.0#30;m        d        #30;cr cn|||||||||#30;100410s2010    onc     o     000 f eng d#30;  #31;a9781426847684 (electronic bk. : Adobe Digital Editions)#30;  #31;a1426847688 (electronic bk. : Adobe Digital Editions)#30;  #31;a9781426847684 (electronic bk. : Mobipocket Reader)#30;  #31;a1426847688 (electronic bk. : Mobipocket Reader)#30;  #31;a(OCoLC)606205999#30;  #31;bOverDrive, Inc.#31;nhttp://www.overdrive.com#30;  #31;aTEFOD#31;cTEFOD#31;dDAD#30;  #31;aDADA#30;14#31;aPS3619.H77#31;bT95 2010beb#30;04#31;a813.54#31;222#30;  #31;aeBook#30;1 #31;aShowalter, Gena.#30;10#31;aTwice as hot#31;h[electronic resource] :#31;btales of an extra-ordinary girl /#31;cGena Showalter.#30;  #31;aDon Mills, Ont. :#31;bHQN,#31;cc2010.#30;  #31;a424 p. ;#31;c17 cm.#30;  #31;aBelle Jamison is finally starting to feel like a normal girl again. Her job as a paranormal investigator is going well, she's learned to control her supernatural abilities (mostly) and she's just gotten engaged to Rome Masters, the ultra-sexy operative who once tried to neutralize her! But planning a wedding is never easy, especially when the bride keeps accidentally torching her dress, the groom returns from a dangerous mission with selective memory loss and the man responsible now wants Belle for himself. With Rome's ex determined to win him back and a new band of supervillains on the horizon, it will take all Belle's powers - plus a little help from her trusty empath sidekick - to save the day, salvage the wedding and prove that true love really does conquer all.#30;  #31;aElectronic reproduction.#31;bToronto, Ontario :#31;cHQN,#31;d2010.#31;nRequires Adobe Digital Editions (file size: 1762 KB) or Adobe Digital Editions (file size: 428 KB) or Mobipocket Reader (file size: 367 KB).#30; 0#31;aWeddings#31;vFiction.#30; 0#31;aParanormal fiction.#30; 4#31;aDownloadable Overdrive ebooks.#30; 7#31;aLove stories.#31;2gsafd#30; 7#31;aElectronic books.#31;2local#30;1 #31;cOriginal#31;z0373774370#31;w(OCoLC)432980664#30;40#31;uhttp://www.emedia2go.org/ContentDetails.htm?ID=BC668E39-F1A7-419D-9287-9A53C90971CE#31;yClick here to Download e-Book#30;  #31;aLTI 01/12/2012#30;  #31;a988941#31;bHorizon bib##30;  #31;aC0#31;bDAD#30;#29;"; 
	private $service;
	private $marcFile; 
	
	public function setUp()
	{
		$this->marcFile = dirname(__FILE__).'/../testFiles/ACSItemTest.mrc';
		parent::setUp();		
	}
	
	/**
	* method next
	* when called
	* should wordProperly
	*/
	public function test_next_called_callNextMethod()
	{
		$this->service = new FileMarc($this->marcFile);
		$expected = 'FileMARCRecord';
		$actual = $this->service->next();
		$this->assertEquals($expected,get_class($actual));
		$field = $actual->getFields('655');
		$this->assertNotEmpty($field);
	}
	
	/**
	 * method next
	 * when calledTwice
	 * should secondCallReturnFalse
	 */
	public function test_next_calledTwice_secondCallReturnFalse()
	{
		$this->service = new FileMarc($this->marcFile);
		$expected = 'FileMARCRecord';
		
		$actual1 = $this->service->next();
		$this->assertTrue(is_object($actual1));
		
		$actual2 = $this->service->next();
		$this->assertFalse($actual2);
	}
	
	/**
	* method next
	* when MarcRecordProvidedAsString
	* should executesCorrectly
	*/
	public function test_next_MarcRecordProvidedAsString_executesCorrectly()
	{
		$this->service = new FileMarc($this->stringMarcRecord, File_Marc::SOURCE_STRING);
		$expected = 'FileMARCRecord';
		$actual1 = $this->service->next();
		$this->assertTrue(is_object($actual1));
	}
	
	
	
	
}
?>