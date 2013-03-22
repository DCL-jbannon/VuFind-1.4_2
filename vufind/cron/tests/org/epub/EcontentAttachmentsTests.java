package org.epub;

import static org.junit.Assert.*;

import java.io.File;
import java.io.IOException;
import java.sql.Connection;
import java.sql.SQLException;
import java.util.ArrayList;

import org.apache.log4j.Logger;
import org.dcl.acs.ACSPackage;
import org.dcl.db.DBeContentRecordServices;
import org.dcl.file.FindFile;
import org.dcl.Utils.FileUtils;
import org.dcl.Utils.ISBNUtils;
import org.ini4j.Ini;
import org.ini4j.Profile.Section;
import org.junit.Before;
import org.junit.Test;
import org.mockito.Mockito;
import org.vufind.CronLogEntry;
import org.vufind.IProcessHandler;


public class EcontentAttachmentsTests 
{
	private String source = "aDummySource";
	private String rawISBN = "aDummyDirtyISBN";
	private String cleanISBN = "aDummyCleanISBN";
	private String recordId = "aDummyEcontentRecordId";
	private String title = "aDummyTitle";
	private String availableCopies = "aDummyAvailableCopies";
	private String path = "aDummyPath";
	private String acsId = "aDummyACSid";
	private String extensionEcontentItem = "pdf";
	private String recordIdFirtsEcontentRecord = "aDummyRecordId_1";
	private String recordIdSecondEcontentRecord = "aDummyRecordId_2";
	
	private String fileNameOriginalNameToAttachACS = this.cleanISBN + ".pdf";
	private String fileNameEcontentItem = this.source + "_" + this.fileNameOriginalNameToAttachACS;
	
	private String fileNameOriginalNameToAttachImage = this.cleanISBN + ".png";
	
	private String absolutePathFileToACS = "aDummyFileToACSAbsolutePath";
	private String absolutePathFileImage = "aDummyFileImageAbsolutePath";
	
	private String destDirFileToACS = "aDummyDestDirTo";
	private String destDirOriginalFolder = "aDummyDestDirToOriginalFolder";

	private EcontentAttachments service;
	private DBeContentRecordServices dbEcontentRecordServicesMock;
	private ISBNUtils isbnUtilsMock;
	private FindFile findFileMock;
	private FileUtils fileUtilsMock;
	private ACSPackage acsPackageMock;
	
	private File fileMock;

	@Before
	public void setUp() throws Exception 
	{
		this.dbEcontentRecordServicesMock = Mockito.mock(DBeContentRecordServices.class);
		this.isbnUtilsMock = Mockito.mock(ISBNUtils.class);
		this.findFileMock = Mockito.mock(FindFile.class);
		this.fileUtilsMock = Mockito.mock(FileUtils.class);
		this.acsPackageMock = Mockito.mock(ACSPackage.class);
		
		this.fileMock = Mockito.mock(File.class);
		
		this.service = new EcontentAttachments(this.dbEcontentRecordServicesMock,
											   this.isbnUtilsMock,
											   this.findFileMock,
											   this.fileUtilsMock,
											   this.acsPackageMock);
	}
	
	/**
	 * method run
	 * when noItemLess
	 * should executesCorrectly
	 * @throws SQLException 
	 * @throws IOException 
	 */
	@Test
	public void test_run_noItemLess_executesCorrectly() throws SQLException, IOException
	{
		ArrayList<ArrayList<String>> result = new ArrayList<ArrayList<String>>();
		
		this.whenGetACSEcontentItemLessBySource(result);
		this.whenIsbnUtilsMock("");
		
		this.service.runACSAttachments(this.source, this.path, this.destDirFileToACS, this.destDirOriginalFolder);
		
		this.verifyGetACSEcontentItemLessBySource(1);
		this.verifyIsbnUtilsMock(0);
	}
	
	/**
	 * method run
	 * when emptyISBN
	 * should executesCorrectly
	 * @throws SQLException 
	 * @throws IOException 
	 */
	@Test
	public void test_run_emptyISBN_executesCorrectly() throws SQLException, IOException
	{
		ArrayList<ArrayList<String>> result = this.getResultGetACSEcontentItemLess();
		
		this.whenGetACSEcontentItemLessBySource(result);
		this.whenIsbnUtilsMock("");
		
		this.service.runACSAttachments(this.source, this.path, this.destDirFileToACS, this.destDirOriginalFolder);
		
		this.verifyGetACSEcontentItemLessBySource(1);
		this.verifyIsbnUtilsMock(2);//No returning anything when looking for coverLess. It is tested on the last test
		this.verifyGetListFilesExist(0);
	}
	
	/**
	 * method run
	 * when noFilesFound
	 * should executesCorrectly
	 * @throws SQLException 
	 * @throws IOException 
	 */
	@Test
	public void test_run_noFilesFound_executesCorrectly() throws SQLException, IOException
	{
		ArrayList<ArrayList<String>> result = this.getResultGetACSEcontentItemLess();
		ArrayList<ArrayList<Object>> resultFindFile = this.getListAfterFindFileNotFoundAnyFile();
		
		this.whenGetACSEcontentItemLessBySource(result);
		this.whenIsbnUtilsMock(this.cleanISBN);
		this.whenGetListFilesExist(resultFindFile);
		
		this.service.runACSAttachments(this.source, this.path, this.destDirFileToACS, this.destDirOriginalFolder);
		
		this.verifyGetACSEcontentItemLessBySource(1);
		this.verifyIsbnUtilsMock(2);//No returning anything when looking for coverLess. It is tested on the last test
		this.verifyGetListFilesExist(1);
		this.verifyAttacheEcontent(0);
	}
	
	/**
	 * method run
	 * when filesFoundNotAttached
	 * should executesCorrectly
	 * @throws SQLException 
	 * @throws IOException 
	 */
	@Test
	public void test_run_filesFoundNotAttached_executesCorrectly() throws SQLException, IOException
	{
		ArrayList<ArrayList<String>> result = this.getResultGetACSEcontentItemLess();
		ArrayList<ArrayList<Object>> resultFindFile = this.getListAfterFindFileFoundFilesEpubPDF();
		
		this.whenGetACSEcontentItemLessBySource(result);
		this.whenIsbnUtilsMock(this.cleanISBN);
		this.whenGetListFilesExist(resultFindFile);
		this.whenAttacheEcontent(null);
		
		this.service.runACSAttachments(this.source, this.path, this.destDirFileToACS, this.destDirOriginalFolder);
		
		this.verifyGetACSEcontentItemLessBySource(1);
		this.verifyIsbnUtilsMock(2); //No returning anything when looking for coverLess. It is tested on the last test
		this.verifyGetListFilesExist(1);
		this.verifyAttacheEcontent(1);
	}
	
	/**
	 * method run
	 * when filesFoundAttached
	 * should executesCorrectly
	 * @throws SQLException 
	 * @throws IOException 
	 */
	@Test
	public void test_run_filesFoundAttachedd_executesCorrectly() throws SQLException, IOException
	{
		ArrayList<ArrayList<String>> resultItemLess = this.getResultGetACSEcontentItemLess();
		ArrayList<ArrayList<String>> resultCoverLess = this.getResultGetCoverLess();
		
		ArrayList<ArrayList<Object>> resultFindFileEpubEPdf = this.getListAfterFindFileFoundFilesEpubPDF();
		ArrayList<ArrayList<Object>> resultFindFileImages = this.getListAfterFindFileFoundFilesImages();
		
		this.whenGetACSEcontentItemLessBySource(resultItemLess);
		this.whenGetEcontentNoCoverBySource(resultCoverLess);
		
		this.whenIsbnUtilsMock(this.cleanISBN);
		this.whenGetListFilesExist(resultFindFileEpubEPdf);
		this.whenAttacheEcontent(this.acsId);
		this.whenInsertEcontentItem();
		this.whenFileMock();
		this.whenFileUtilsMockMoveAcsFile();
		//Images
		this.whenGetListFilesExistImages(resultFindFileImages);
		this.whenFileUtilsMockMoveImageFile();
		this.whenUpdateCoverEcontentRecord();
		
		this.service.runACSAttachments(this.source, this.path, this.destDirFileToACS, this.destDirOriginalFolder);
		
		this.verifyGetACSEcontentItemLessBySource(1);
		this.verifyIsbnUtilsMock(4);
		this.verifyGetListFilesExist(1);
		this.verifyAttacheEcontent(1);
		this.verifyInsertEcontentItem(1);
		this.verifyFileMock(2);
		this.verifyFileUtilsMockMoveAcsFile(1);
		
		//Images
		this.verifyGetEcontentNoCoverBySource(1);
		this.verifyGetListFilesExistImages(1);
		this.verifyFileUtilsMockMoveImageFile(1);
		this.verifyUpdateCoverEcontentRecord(1);
	}
	
	//Prepares
	private void whenGetACSEcontentItemLessBySource(ArrayList<ArrayList<String>> result) throws SQLException
	{
		Mockito.when(this.dbEcontentRecordServicesMock.getACSEcontentItemLessBySource(this.source)).thenReturn(result);
	}
	
	private void whenGetEcontentNoCoverBySource(ArrayList<ArrayList<String>> result) throws SQLException
	{
		Mockito.when(this.dbEcontentRecordServicesMock.getEcontentNoCoverBySource(this.source)).thenReturn(result);
	}
		
	private void whenIsbnUtilsMock(String result) throws SQLException
	{
		Mockito.when(this.isbnUtilsMock.detectGetISBN(this.rawISBN)).thenReturn(result);
	}
	
	private void whenGetListFilesExist(ArrayList<ArrayList<Object>> resultFindFile) throws SQLException
	{
		Mockito.when(this.findFileMock.getListFilesExist(this.path, this.getFileNameFindToAcsAttach())).thenReturn(resultFindFile);
	}
	
	private void whenGetListFilesExistImages(ArrayList<ArrayList<Object>> resultFindFile) throws SQLException
	{
		Mockito.when(this.findFileMock.getListFilesExist(this.path, this.getFileNameFindToAttachImages())).thenReturn(resultFindFile);
	}
	
	private void whenAttacheEcontent(String resultAttachment) throws SQLException
	{
		Mockito.when(this.acsPackageMock.addFile(this.fileMock, this.availableCopies)).thenReturn(resultAttachment);
	}
	
	private void whenInsertEcontentItem() throws SQLException
	{
		Mockito.when(this.dbEcontentRecordServicesMock.insertEcontentItem(this.fileNameEcontentItem, this.acsId, this.recordIdFirtsEcontentRecord, this.extensionEcontentItem)).thenReturn(true);
	}
	
	private void whenUpdateCoverEcontentRecord() throws SQLException
	{
		Mockito.when(this.dbEcontentRecordServicesMock.updateEcontentCover("aDummyRecordId_2", this.fileNameOriginalNameToAttachImage)).thenReturn(true);
	}
	
	private void whenFileMock()
	{
		Mockito.when(this.fileMock.getName()).thenReturn(this.fileNameOriginalNameToAttachACS).thenReturn(this.fileNameOriginalNameToAttachImage);
		Mockito.when(this.fileMock.getAbsolutePath()).thenReturn(this.absolutePathFileToACS).thenReturn(this.absolutePathFileImage);
	}
	
	private void whenFileUtilsMockMoveAcsFile() throws IOException
	{
		String destFile =  this.destDirFileToACS + "/" + this.fileNameEcontentItem;
		Mockito.when(this.fileUtilsMock.copyFilesByPath(this.absolutePathFileToACS, destFile)).thenReturn(true);
	}
	
	private void whenFileUtilsMockMoveImageFile() throws IOException
	{
		String destFile =  this.destDirOriginalFolder + "/" + this.fileNameOriginalNameToAttachImage;
		Mockito.when(this.fileUtilsMock.copyFilesByPath(this.absolutePathFileImage, destFile)).thenReturn(true);
	}
	
	private void verifyIsbnUtilsMock(int verifyTimes) throws SQLException
	{
		Mockito.verify(this.isbnUtilsMock, Mockito.times(verifyTimes)).detectGetISBN(this.rawISBN);
	}
	
	private void verifyGetACSEcontentItemLessBySource(int verifyTimes) throws SQLException
	{
		Mockito.verify(this.dbEcontentRecordServicesMock, Mockito.times(1)).getACSEcontentItemLessBySource(this.source);
	}
	
	private void verifyGetEcontentNoCoverBySource(int verifyTimes) throws SQLException
	{
		Mockito.verify(this.dbEcontentRecordServicesMock, Mockito.times(1)).getEcontentNoCoverBySource(this.source);
	}
	
	private void verifyGetListFilesExist(int verifyTimes) throws SQLException
	{
		Mockito.verify(this.findFileMock, Mockito.times(verifyTimes)).getListFilesExist(this.path, this.getFileNameFindToAcsAttach());
	}
	
	private void verifyGetListFilesExistImages(int verifyTimes) throws SQLException
	{
		Mockito.verify(this.findFileMock, Mockito.times(verifyTimes)).getListFilesExist(this.path, this.getFileNameFindToAttachImages());
	}
	
	private void verifyAttacheEcontent(int verifyTimes)
	{
		Mockito.verify(this.acsPackageMock, Mockito.times(verifyTimes)).addFile(this.fileMock, this.availableCopies);
	}
	
	private void verifyInsertEcontentItem(int verifyTimes) throws SQLException
	{
		Mockito.verify(this.dbEcontentRecordServicesMock, Mockito.times(verifyTimes)).insertEcontentItem(this.fileNameEcontentItem, this.acsId, "aDummyRecordId_1", this.extensionEcontentItem);
	}
	
	private void verifyFileMock(int verifyTimes)
	{
		Mockito.verify(this.fileMock, Mockito.times(verifyTimes)).getName();
		Mockito.verify(this.fileMock, Mockito.times(verifyTimes)).getAbsolutePath();
	}
	
	private void verifyFileUtilsMockMoveAcsFile(int verifyTimes) throws IOException
	{
		String destFile =  this.destDirFileToACS + "/" + this.fileNameEcontentItem;
		Mockito.verify(this.fileUtilsMock, Mockito.times(verifyTimes)).copyFilesByPath(this.absolutePathFileToACS, destFile);
	}
	
	private void verifyFileUtilsMockMoveImageFile(int verifyTimes) throws IOException
	{
		String destFile =  this.destDirOriginalFolder + "/" + this.fileNameOriginalNameToAttachImage;
		Mockito.verify(this.fileUtilsMock, Mockito.times(verifyTimes)).copyFilesByPath(this.absolutePathFileImage, destFile);
	}
	
	private void verifyUpdateCoverEcontentRecord(int verifyTimes) throws SQLException
	{
		Mockito.verify(this.dbEcontentRecordServicesMock, Mockito.times(verifyTimes)).updateEcontentCover("aDummyRecordId_2", this.fileNameOriginalNameToAttachImage);
	}
	
/***************************************
 ********** UTILS AND PREPARES *********
 ***************************************/
	private ArrayList<ArrayList<String>> getResultGetACSEcontentItemLess()
	{
		ArrayList<ArrayList<String>> result = new ArrayList<ArrayList<String>>();
		result.add(this.getRowResultGetACSEcontentItemLess(this.recordIdFirtsEcontentRecord, this.availableCopies));
		result.add(this.getRowResultGetACSEcontentItemLess(this.recordIdSecondEcontentRecord, this.availableCopies+"0" ));
		return result;
	}
	
	private ArrayList<ArrayList<String>> getResultGetCoverLess()
	{
		//Return The same row structure
		return this.getResultGetACSEcontentItemLess();
	}
	
	
	private ArrayList<String> getRowResultGetACSEcontentItemLess(String recordId, String availableCopies)
	{
		ArrayList<String> row = new ArrayList<String>();
		row.add(recordId);
		row.add(this.title);
		row.add(availableCopies);
		row.add(this.rawISBN);
		return row;
	}
	
	private ArrayList<String> getRowResultGetACSEcontentItemLess()
	{
		return this.getRowResultGetACSEcontentItemLess(this.recordId, this.availableCopies);
	}
	
	private ArrayList<ArrayList<Object>> getFileNameFindToAcsAttach()
	{
		ArrayList<ArrayList<Object>> filenamesToSearch = new ArrayList<ArrayList<Object>>();
		filenamesToSearch.add(this.getElementListFilesToSearch(this.cleanISBN + ".epub", this.getRowResultGetACSEcontentItemLess(this.recordIdFirtsEcontentRecord,  this.availableCopies)));
		filenamesToSearch.add(this.getElementListFilesToSearch(this.cleanISBN + ".pdf",  this.getRowResultGetACSEcontentItemLess(this.recordIdFirtsEcontentRecord,  this.availableCopies)));
		filenamesToSearch.add(this.getElementListFilesToSearch(this.cleanISBN + ".epub", this.getRowResultGetACSEcontentItemLess(this.recordIdSecondEcontentRecord, this.availableCopies+"0")));
		filenamesToSearch.add(this.getElementListFilesToSearch(this.cleanISBN + ".pdf",  this.getRowResultGetACSEcontentItemLess(this.recordIdSecondEcontentRecord, this.availableCopies+"0")));
		return filenamesToSearch;
	}
	
	private ArrayList<ArrayList<Object>> getFileNameFindToAttachImages()
	{
		ArrayList<ArrayList<Object>> filenamesToSearch = new ArrayList<ArrayList<Object>>();
		filenamesToSearch.add(this.getElementListFilesToSearch(this.cleanISBN + ".jpg", this.getRowResultGetACSEcontentItemLess(this.recordIdFirtsEcontentRecord,   this.availableCopies)));
		filenamesToSearch.add(this.getElementListFilesToSearch(this.cleanISBN + ".png",  this.getRowResultGetACSEcontentItemLess(this.recordIdFirtsEcontentRecord,  this.availableCopies)));
		filenamesToSearch.add(this.getElementListFilesToSearch(this.cleanISBN + ".jpg", this.getRowResultGetACSEcontentItemLess(this.recordIdSecondEcontentRecord,  this.availableCopies+"0")));
		filenamesToSearch.add(this.getElementListFilesToSearch(this.cleanISBN + ".png",  this.getRowResultGetACSEcontentItemLess(this.recordIdSecondEcontentRecord, this.availableCopies+"0")));
		return filenamesToSearch;
	}
	
	private ArrayList<Object> getElementListFilesToSearch(String fileName, Object object)
	{
		ArrayList<Object> element = new ArrayList<Object>();
		element.add(fileName);
		element.add(object);
		return element;
	}
	
	private ArrayList<ArrayList<Object>> getListAfterFindFileNotFoundAnyFile()
	{
		ArrayList<ArrayList<Object>> fileList = this.getFileNameFindToAcsAttach();
		for(int i=0; i<fileList.size(); i++)
		{
			fileList.get(i).add(null);
		}
		return fileList;
	}
	
	private ArrayList<ArrayList<Object>> getListAfterFindFileFoundFilesEpubPDF()
	{
		ArrayList<ArrayList<Object>> fileList = this.getFileNameFindToAcsAttach();
		fileList.get(0).add(null);
		fileList.get(1).add(this.fileMock);
		fileList.get(2).add(null);
		fileList.get(3).add(null);
		return fileList;
	}
	
	private ArrayList<ArrayList<Object>> getListAfterFindFileFoundFilesImages()
	{
		ArrayList<ArrayList<Object>> fileList = this.getFileNameFindToAttachImages();
		fileList.get(0).add(null);
		fileList.get(1).add(null);
		fileList.get(2).add(this.fileMock);
		fileList.get(3).add(null);
		return fileList;
	}
	
}