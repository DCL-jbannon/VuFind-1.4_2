package org.dcl.file;
//http://code.google.com/p/junitparams/
import static org.junit.Assert.*;

import java.io.File;
import java.util.ArrayList;
import org.junit.Test;

public class FindFileTests extends BaseFindFileTests
{
	/**
	 * method getListFilesExist
	 * when notFound
	 * should returnEmptyString
	 */
	@Test
	public void test_getListFilesExist_notFound_returnEmptyString()
	{
		ArrayList<ArrayList<Object>> expected;
		ArrayList<ArrayList<Object>> filenames = new ArrayList<ArrayList<Object>>();
		
		ArrayList<Object> file = new ArrayList<Object>();
		file.add("aNonExistingFile.ext");
		file.add("aDummyObject");
		
		filenames.add(file);
		expected = filenames;
		
		ArrayList<ArrayList<Object>> actual = this.service.getListFilesExist(this.path, filenames);
		assertEquals(expected, actual);
	}
}