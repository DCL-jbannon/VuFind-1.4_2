/***
 * http://www.dbunit.org/howto.html
 */
package db;

import static org.junit.Assert.*;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;

import org.dcl.db.DBeContentRecordServices;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.junit.runners.Parameterized;



public class DBeContentRecordServicesTests extends BaseDBEcontentTests
{	
	private DBeContentRecordServices service;

	@Before
	public void setUp() throws Exception 
	{
		this.service = new DBeContentRecordServices(BaseDBTests.conn);
	}
	
	/**
	 * method getACSEcontentItemLessBySource
	 * when noItemLess
	 * should returnEmptyArrayList
	 * @throws SQLException 
	 */
	@Test
	public void test_getACSEcontentItemLessBySource_noItemLess_returnEmpty() throws SQLException
	{
		ArrayList<ArrayList<String>> expected = new ArrayList<ArrayList<String>>();
		String source = "aDummySourceValue";
		ArrayList<ArrayList<String>> actual = this.service.getACSEcontentItemLessBySource(source);
		assertEquals(expected, actual);
	}
	
	/**
	 * method getACSEcontentItemLessBySource
	 * when called
	 * should returnCorrectArrayList
	 * @throws SQLException 
	 */
	@Test
	public void test_getACSEcontentItemLessBySource_called_returnCorrectArrayList() throws SQLException
	{
		String source = "aDummySourceValue";
		this.service.insertEContentRecord("aDummyTitle", source, "acs", "aDummyISBN", "99");
		
		ArrayList<ArrayList<String>> expected = new ArrayList<ArrayList<String>>();
		ArrayList<String> row = new ArrayList<String>();
		row.add("1");
		row.add("aDummyTitle");
		row.add("99");
		row.add("aDummyISBN");
		expected.add(row);
		
		ArrayList<ArrayList<String>> actual = this.service.getACSEcontentItemLessBySource(source);
		assertEquals(1, actual.size());
		assertEquals(expected, actual);
	}
	
	/**
	 * method insertEcontentItem
	 * when called
	 * should executesCorrectly
	 * @throws SQLException 
	 */
	@Test
	public void test_insertEcontentItem_called_executesCorrectly() throws SQLException
	{
		String filename = "aDummyFileName";
		String acsId = "aDummyACSID";
		String recordId = "1";
		String item_type = "pdf"; //'epub','pdf','jpg','gif','mp3','plucker','kindle','externalLink','externalMP3','interactiveBook','overdrive'
		
		Boolean actual = this.service.insertEcontentItem(filename, acsId, recordId, item_type);
		assertTrue(actual);
	}
	
	/**
	 * method updateCoverEcontentRecord
	 * when called
	 * should executesCorrectly
	 * @throws SQLException 
	 */
	@Test
	public void test_updateCoverEcontentRecordr_called_executesCorrectly() throws SQLException
	{
		this.service.insertEContentRecord("aDummyTitle", "aDummySource", "acs", "aDummyISBN", "99");
		String cover = "aDummyCoverFileName";
		Boolean actual = this.service.updateEcontentCover("1", cover);
		assertTrue(actual);
	}
	
	
	/**
	 * method getEcontentNoCoverBySource
	 * when allHaveCover
	 * should returnEmptyArrayList
	 * @throws SQLException 
	 */
	@Test
	public void test_getEcontentNoCoverBySourcer_allHaveCover_returnEmptyArrayList() throws SQLException
	{
		ArrayList<ArrayList<String>> expected = new ArrayList<ArrayList<String>>();
		String source = "aDummySourceValue";
		ArrayList<ArrayList<String>> actual = this.service.getEcontentNoCoverBySource(source);
		assertEquals(expected, actual);
	}
	
	/**
	 * method getEcontentNoCoverBySource
	 * when called
	 * should executeCorrectly
	 * @throws SQLException 
	 */
	@Test
	public void test_getEcontentNoCoverBySourcer_called_executeCorrectly() throws SQLException
	{
		ArrayList<ArrayList<String>> expected = new ArrayList<ArrayList<String>>();
		ArrayList<String> row = new ArrayList<String>();
		row.add("1");
		row.add("aDummyTitle");
		row.add("99");
		row.add("aDummyISBN");
		expected.add(row);
		String source = "aDummySourceValue";
		this.service.insertEContentRecord("aDummyTitle", source, "acs", "aDummyISBN", "99", "");
		
		ArrayList<ArrayList<String>> actual = this.service.getEcontentNoCoverBySource(source);
		assertEquals(expected, actual);
	}

	@After
    public void tearDown() throws SQLException
	{
		PreparedStatement stmt = this.conn.prepareStatement("TRUNCATE TABLE econtent_record");
		stmt.execute();
		stmt = this.conn.prepareStatement("TRUNCATE TABLE econtent_item");
		stmt.execute();
    }
}