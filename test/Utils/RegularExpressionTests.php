<?php

require_once dirname(__FILE__).'/../../vufind/classes/Utils/RegularExpressions.php';

class RegularExpressionsTests extends PHPUnit_Framework_TestCase
{
	
	private $service;
	
	public function setUp()
	{
		$this->service = new RegularExpressions();
		parent::setUp();		
	}
	
	
	/**
	* method addAnchorHrefLink
	* when called
	* should worksCorrectly
	*/
	public function test_addAnchorHrefLink_called_worksCorrectly()
	{
		$htmltest = '<p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="section-0026.html">Acknowledgements</a></p>
					 <p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="mailto:jgimenez@dclibraries.org">About the Author - Cynthia T. Kennedy</a></p>
					 <p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="https://www.google.es">Copyright</a></p>
                     <p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="section-0028.html">Copyright</a></p>
					 <p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="http://www.google.es">Copyright</a></p>';
		
		$expected = '<p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="#section-0026.html">Acknowledgements</a></p>
					 <p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="mailto:jgimenez@dclibraries.org">About the Author - Cynthia T. Kennedy</a></p>
					 <p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="https://www.google.es">Copyright</a></p>
                     <p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="#section-0028.html">Copyright</a></p>
					 <p class="P_TOC_Entry_1" style = "margin-left: 2em;"><a href="http://www.google.es">Copyright</a></p>';
		
		$actual = $this->service->addAnchorHrefLink($htmltest);
		$this->assertEquals($expected, $actual);
		
	}
	
	/**
	* method getFieldValueFromURL
	* when called
	* should returnCorrectId
	* @dataProvider DP_getFieldValueFromURL
	*/
	public function test_getFieldValueFromURL_called_returnCorrectId($url)
	{
		$paramName = "ID";
		$expected = '0EA036C9-C4DB-4A87-A741-5CC03BF9D96E';
		$actual = $this->service->getFieldValueFromURL($url, $paramName);
		$this->assertEquals($expected, $actual);
	}
	
	public function DP_getFieldValueFromURL()
	{
		return array(
				  		array("http://www.emedia2go.org/ContentDetails.htm?ID=0EA036C9-C4DB-4A87-A741-5CC03BF9D96E"),
						array("http://www.emedia2go.org/ContentDetails.htm?ID=0EA036C9-C4DB-4A87-A741-5CC03BF9D96E&anotherParam=aDummyValue"),
						array("http://www.emedia2go.org/ContentDetails.htm?anotherParam1=aDummyValue&ID=0EA036C9-C4DB-4A87-A741-5CC03BF9D96E&anotherParam2=aDummyValue2")
					);
	}
	
	/**
	 * method getFieldValueFromURL
	 * when emptyField
	 * should returnEmpty
	 */
	public function test_getFieldValueFromURL_emptyField_returnEmpty()
	{
		$url="http://www.aDummyUrl.org/BLABLABLA?ID=";
		$paramName = "ID";
		$expected = '';
		$actual = $this->service->getFieldValueFromURL($url, $paramName);
		$this->assertEquals($expected, $actual);
	}
	
	
	/**
	* method getFieldValueFromURL
	* when FieldCannotBeFound
	* should returnEmpty
	*/
	public function test_getFieldValueFromURL_FieldCannotBeFound_returnEmpty()
	{
		$url="aDummyUrlWithNoParameter";
		$paramName = "aDummyParameter";
		$expected = '';
		$actual = $this->service->getFieldValueFromURL($url, $paramName);
		$this->assertEquals($expected, $actual);
	}

}

?>