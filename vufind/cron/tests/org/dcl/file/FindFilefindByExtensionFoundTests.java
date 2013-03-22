package org.dcl.file;
//http://code.google.com/p/junitparams/
import static org.junit.Assert.*;
import java.io.File;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collection;

import org.dcl.file.FindFile;
import org.junit.Before;
import org.junit.Test;
import org.mockito.Mockito;
import org.junit.runner.RunWith;
import org.junit.runners.Parameterized;
import org.junit.runners.Parameterized.Parameters;

@RunWith(value = Parameterized.class)
public class FindFilefindByExtensionFoundTests extends BaseFindFileTests
{
	private ArrayList<ArrayList<Object>> filenamesToSearch;
	private ArrayList<ArrayList<Object>> expected;
	private static String baseTestPath = "C:\\projects\\VuFind-Plus\\vufind\\cron\\fileTests\\";
	
	public FindFilefindByExtensionFoundTests(String filenamesToSearch, String pathsExpected)
	{
		this.expected = new ArrayList<ArrayList<Object>>(); 
		this.filenamesToSearch = new ArrayList<ArrayList<Object>>();
		ArrayList<String> listFiles = this.splitIntoArrayListString(filenamesToSearch);
		ArrayList<String> listAbsolutePath = this.splitIntoArrayListString(pathsExpected);
		
		for (int i =0; i<listFiles.size();i++)
		{
			ArrayList<Object> element = new ArrayList<Object>();
			element.add(listFiles.get(i));
			element.add("Whatever. Could be any Object --> " + i);
			
			ArrayList<Object> elementExpected = new ArrayList<Object>();
			
			elementExpected.add(listFiles.get(i));
			elementExpected.add("Whatever. Could be any Object --> " + i);
			if (listAbsolutePath.size()>= (i+1))
			{
				elementExpected.add(new File(listAbsolutePath.get(i)));
			}
			else
			{
				elementExpected.add(null);
			}
			
			this.filenamesToSearch.add(element);
			this.expected.add(elementExpected);
		}
	}
	
	/**
	 * method getListFilesExist
	 * when found
	 * should returnFullPathFilename
	 */
	@Test
	public void test_getListFilesExistn_found_returnFullPathFilename()
	{
		ArrayList<ArrayList<Object>> actual = this.service.getListFilesExist(this.path, this.filenamesToSearch);
		assertEquals(this.expected, actual);
	}
	
	@Parameters
	 public static Collection<Object[]> data()
	 {
	   Object[][] data = new Object[][]{
			   								{ "aDummy.epub", baseTestPath + "aDummy.epub"},
			   								{ "aDummy.pdf", baseTestPath + "aDummy.pdf"}, 
			   								{ "aDummy.png", baseTestPath + "aDummy.png"},
			   								{ "aDummy.jpg, NonExistingFile.txt", baseTestPath + "aDummy.jpg"},
			   								{ "aDummyPDF-B21.pdf,aDummyEPUB-B21.epub",  baseTestPath + "b\\b2\\b21\\aDummyPDF-B21.pdf," + baseTestPath + "b\\b2\\b21\\aDummyEPUB-B21.epub"},
			   								{ "aDummyPDF-C.pdf", 	baseTestPath + "c\\aDummyPDF-C.pdf"},
			   								{ "aDummy.epub,aDummy.jpg,aDummy.pdf,aDummy.png", baseTestPath + "aDummy.epub," + baseTestPath + "aDummy.jpg," + baseTestPath + "aDummy.pdf," + baseTestPath + "aDummy.png"},
			   								{ "aDummy.epub,aDummyEPUB-B21.epub", baseTestPath + "aDummy.epub," + baseTestPath + "b\\b2\\b21\\aDummyEPUB-B21.epub"}
			   							};
	   return Arrays.asList(data);
	 }
	 
	 private ArrayList<String> splitIntoArrayListString(String stringToSplit)
	 {
	    ArrayList<String> arrayListString = new ArrayList<String>();
	 	String[] splitArray = stringToSplit.split(",");
		for (int i = 0; i<splitArray.length; i++)
		{
			arrayListString.add(splitArray[i]);
		}
		return arrayListString;
	 }
}