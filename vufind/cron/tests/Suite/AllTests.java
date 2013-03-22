package Suite;


/**
 * http://www.mkyong.com/unittest/junit-4-tutorial-5-suite-test/
 */

import org.dcl.Utils.FileUtilsTests;
import org.dcl.Utils.ISBNUtilsTests;
import org.dcl.file.FindFileTests;
import org.dcl.file.FindFilefindByExtensionFoundTests;
import org.epub.EcontentAttachmentsTests;
import org.junit.runner.RunWith;
import org.junit.runners.Suite;

import db.DBeContentRecordServicesTests;

@RunWith(Suite.class)
@Suite.SuiteClasses({
		FindFileTests.class,
		FindFilefindByExtensionFoundTests.class,
		ISBNUtilsTests.class,
		FileUtilsTests.class,
		EcontentAttachmentsTests.class,
		DBeContentRecordServicesTests.class
})

public class AllTests
{
}