package org.dcl.Utils;

import static org.junit.Assert.*;

import java.io.File;
import java.io.IOException;

import org.apache.commons.io.FileUtils;
import org.junit.After;
import org.junit.Before;
import org.junit.Rule;
import org.junit.Test;
import org.junit.rules.ExpectedException;

public class FileUtilsTests extends BaseFileUtilsTests
{
	@Rule
	public ExpectedException exception = ExpectedException.none();
	
	/**
	 * method copyFilesByPath
	 * when sourceFileDoesNotExists
	 * should throw
	 * @throws IOException 
	 */
	@Test
	public void test_copyFilesByPath_sourceFileDoesNotExists_throw() throws IOException
	{
		exception.expect(IOException.class);
		String sourceFilePath = "aDummyFileNotExits";
		String destFilePath = "aDummyDestFile";
		this.service.copyFilesByPath(sourceFilePath, destFilePath);
	}
	
	/**
	 * method copyFilesByPath
	 * when destFileCannotBeCreated
	 * should throw
	 * @throws IOException 
	 */
	@Test
	public void test_copyFilesByPath_destFileCannotBeCreated_throw() throws IOException
	{
		exception.expect(IOException.class);
		String sourceFilePath = "c:/projects/VuFind-Plus/vufind/cron/fileTests/aDummy.txt";
		String destFilePath = "c:/projectsAnonExistingDirectoryPleaseDoNotCreateIt/aDummy.txt";
		this.service.copyFilesByPath(sourceFilePath, destFilePath);
	}
	
	/**
	 * method copyFilesByPath
	 * when called
	 * should executesCorrectly
	 * @throws IOException 
	 */
	@Test
	public void test_copyFilesByPath_called_executesCorrectly() throws IOException
	{
		String sourceFilePath = "c:/projects/VuFind-Plus/vufind/cron/fileTests/aDummy.txt";
		String destFilePath = "c:/projects/VuFind-Plus/vufind/cron/fileTests/aDummyDest.txt";
		this.service.copyFilesByPath(sourceFilePath, destFilePath);
		
		File sourceFile = new File("c:/projects/VuFind-Plus/vufind/cron/fileTests/aDummy.txt");
		File actualFile = new File("c:/projects/VuFind-Plus/vufind/cron/fileTests/aDummyDest.txt");
		assertTrue(actualFile.exists());
		assertTrue(FileUtils.contentEquals(sourceFile, actualFile));
		
		this.service.copyFilesByPath(sourceFilePath, destFilePath);
		actualFile = new File("c:/projects/VuFind-Plus/vufind/cron/fileTests/aDummyDest.txt");
		assertTrue(FileUtils.contentEquals(sourceFile, actualFile));
	}
	
	@After
    public void tearDown() 
	{
		File fileToDeleteAfterEachTest = new File("c:/projects/VuFind-Plus/vufind/cron/fileTests/aDummyDest.txt");
		fileToDeleteAfterEachTest.delete();
    }

}