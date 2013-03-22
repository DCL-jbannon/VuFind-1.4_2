package org.dcl.Utils;

import static org.junit.Assert.*;

import java.io.IOException;
import java.util.Arrays;
import java.util.Collection;

import org.junit.Before;
import org.junit.Rule;
import org.junit.Test;
import org.junit.rules.ExpectedException;
import org.junit.runner.RunWith;
import org.junit.runners.Parameterized;
import org.junit.runners.Parameterized.Parameters;

@RunWith(value = Parameterized.class)
public class FileUtilsGetFileExtensionTests extends BaseFileUtilsTests
{
	private String fileName;
	private String expected;
	
	public FileUtilsGetFileExtensionTests(String filename, String expected)
	{
		this.fileName = filename;
		this.expected = expected;
	}
	
	/**
	 * method getFileExtension
	 * when called
	 * should returnCorrectExtension
	 */
	@Test
	public void test_getFileExtension_called_returnCorrectExtension()
	{
		String actual = this.service.getFileExtension(this.fileName);
		assertEquals(this.expected, actual);
	}
	
	@Parameters
	 public static Collection<Object[]> data()
	 {
	   Object[][] data = new Object[][]{
			   								{ "aDummy.epub", "epub"},
			   								{ "aDummy.pdf", "pdf"},
			   								{ "aDum.my.pdf", "pdf"},
			   								{ ".aDummy.pdf", "pdf"},
			   								{ "", ""},
			   								{ "fileNoExtension", ""},
			   								{ "fileNoExtension.", ""},
			   								
			   							};
	   return Arrays.asList(data);
	 }
}