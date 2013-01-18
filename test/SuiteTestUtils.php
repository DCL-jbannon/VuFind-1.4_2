<?php

require_once dirname(__FILE__).'/Utils/ArrayTests.php';
require_once dirname(__FILE__).'/Utils/CheckArgsTests.php';
require_once dirname(__FILE__).'/Utils/ObjectUtilsTests.php';
require_once dirname(__FILE__).'/Utils/DateTimeUtilsTests.php';
require_once dirname(__FILE__).'/Utils/RegularExpressionTests.php';
require_once dirname(__FILE__).'/classes/solr/SolrDOCTests.php';
require_once dirname(__FILE__).'/classes/solr/SolrResponseBodyTests.php';
require_once dirname(__FILE__).'/classes/solr/SolrResponseHeaderTests.php';
require_once dirname(__FILE__).'/classes/solr/SolrResponseTests.php';
require_once dirname(__FILE__).'/Utils/BookCoverURLTests.php';
require_once dirname(__FILE__).'/FileMarc/FileMarcTests.php';
require_once dirname(__FILE__).'/FileMarc/MarcSubFieldTests.php';
require_once dirname(__FILE__).'/classes/covers/TitleNoCoverTests.php';
require_once dirname(__FILE__).'/classes/covers/CoversTypeTests.php';
require_once dirname(__FILE__).'/classes/covers/GoogleBooksCoversTests.php';
require_once dirname(__FILE__).'/classes/covers/OpenLibraryCoversTests.php';
require_once dirname(__FILE__).'/classes/covers/LibraryThingCoversTests.php';
require_once dirname(__FILE__).'/classes/covers/SyndeticsCoversTests.php';
require_once dirname(__FILE__).'/classes/covers/OverDriveCoversTests.php';
require_once dirname(__FILE__).'/classes/covers/AttachedEcontentCoversTests.php';
require_once dirname(__FILE__).'/classes/covers/ThreeMCoversTests.php';
require_once dirname(__FILE__).'/Utils/ThreeMUtilsTests.php';
require_once dirname(__FILE__).'/classes/covers/OriginalFolderCoversTests.php';

class SuiteUtilsTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Util-Tests');
		$suite->addTestSuite('ArrayUtilsTests');
		$suite->addTestSuite('CheckArgsTests');
		$suite->addTestSuite('ObjectUtilsTests');
		$suite->addTestSuite('RegularExpressionsTests');
		$suite->addTestSuite('SolrDOCTests');
		$suite->addTestSuite('SolrResponseBodyTests');
		$suite->addTestSuite('SolrResponseHeaderTests');
		$suite->addTestSuite('SolrResponseTests');
		$suite->addTestSuite('BookCoverURLTests');
		$suite->addTestSuite('FileMarcTests');
		$suite->addTestSuite('MarcSubFieldTests');
		$suite->addTestSuite('TitleNoCoverTests');
		$suite->addTestSuite('CoversTypeTests');
		$suite->addTestSuite('GoogleBooksCoversTests');
		$suite->addTestSuite('OpenLibraryCoversTests');
		$suite->addTestSuite('LibraryThingCoversTests');
		$suite->addTestSuite('SyndeticsCoversTests');
		$suite->addTestSuite('OverDriveCoversTests');
		$suite->addTestSuite('AttachedEcontentCoversTests');
		$suite->addTestSuite('DateTimeUtilsTests');
		$suite->addTestSuite('ThreeMCoversTests');
		$suite->addTestSuite('ThreeMUtilsTests');
        $suite->addTestSuite('OriginalFolderCoversTests');
		return $suite;
	}
}

?>