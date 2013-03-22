package org.vufind.cron;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;
import static org.junit.Assert.assertTrue;
import static org.junit.Assert.fail;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Date;

import org.apache.commons.io.FileUtils;
import org.apache.log4j.Logger;
import org.epub.ImportFreegal;
import org.ini4j.Ini;
import org.ini4j.Profile.Section;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import org.vufind.Base64Coder;
import org.vufind.CronLogEntry;
import org.vufind.TestUtil;
import org.vufind.Util;

public class ImportFreegalTest {
	private static Logger logger = Logger.getLogger(ImportFreegalTest.class);
	private static final String ON_THE_FLY_ALBUM_TITLE = "Created on the fly and should be deleted by the ImportFreegal process";
	private static final String ON_THE_FLY_ALBUM_AUTHOR = "testDeleteAlbumsNotInFreegalAPI";
	private Connection vufindConn;
	private Connection econtentConn;
	private Ini ini;
	private Ini cronIni;
	private Section processSettings;
	private CronLogEntry cronEntry;
	private ImportFreegal importFreegal;
	private long thisRunTimeStamp;
	private String serverName = "dcl.localhost";

	@Before
	public void setUp() throws Exception {
		// Override the serverName with value from system environment
		// if it is set
		String serverNameEnv = System.getenv("VUFIND_PLUS_SERVER_NAME");
		if (serverNameEnv != null && serverNameEnv.length() > 0) {
			serverName = serverNameEnv;
		}

		Class.forName("com.mysql.jdbc.Driver").newInstance();

		// Read the base INI file to get information about the server (current
		// directory/cron/config.ini)
		ini = TestUtil.loadConfigFile("config.ini", serverName);

		// Connect to the database
		String databaseConnectionInfo = Util.cleanIniValue(ini.get("Database",
				"database_vufind_jdbc"));
		if (databaseConnectionInfo == null
				|| databaseConnectionInfo.length() == 0) {
			throw new Exception(
					"VuFind Database connection information not found in General Settings.  Please specify connection information in a database key.");
		}
		vufindConn = DriverManager.getConnection(databaseConnectionInfo);
		vufindConn.setAutoCommit(false);

		String econtentConnectionInfo = Util.cleanIniValue(ini.get("Database",
				"database_econtent_jdbc"));
		if (econtentConnectionInfo == null
				|| econtentConnectionInfo.length() == 0) {
			throw new Exception(
					"eContent Database connection information not found in General Settings.  Please specify connection information in a database key.");
		}
		econtentConn = DriverManager.getConnection(econtentConnectionInfo);
		econtentConn.setAutoCommit(false);

		// add some info to the log so we can see
		logger.info("In setUp() - original eContent records count = "
				+ countEContentRecords());
		logger.info("In setUp() - original eContent items count = "
				+ countEContentItems());

		// Create a log entry for the cron process
		cronEntry = new CronLogEntry();
		if (!cronEntry.saveToDatabase(vufindConn, logger)) {
			throw new Exception("Could not save log entry to database");
		}

		// Read the cron INI file to get information about the processes to run
		cronIni = TestUtil.loadConfigFile("config.cron.ini", serverName);

		// Get configuration for the ImportFreegal process
		processSettings = cronIni.get("ImportFreegal");
		String freegalUser = processSettings.get("freegalUser");
		String freegalPIN = processSettings.get("freegalPIN");
		String freegalAPIkey = processSettings.get("freegalAPIkey");
		String freegalLibrary = processSettings.get("freegalLibrary");

		// make sure we don't commit/optimize solr changes
		processSettings.put("commitSolr", "false");
		processSettings.put("optimizeSolr", "false");

		// create new instance of process handler
		importFreegal = new ImportFreegal();

		// Create a directory:
		// web/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser
		// so it can be accessed as
		// http://vufindUrl/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser
		File genresDir = new File(ini.get("Site", "local")
				+ "/files/freegal/services/genre/" + freegalAPIkey + "/"
				+ freegalLibrary + "/" + freegalUser);
		if (!genresDir.mkdirs()) {
			// chances are the directory was created during last test run
			// but was not deleted cleanly for some reason
			if (genresDir.exists() && genresDir.isDirectory()) {
				// delete the directory
				FileUtils.deleteDirectory(new File(ini.get("Site", "local")
						+ "/files/freegal"));
				// retry directory creation
				if (!genresDir.mkdirs()) {
					// give up, something is not right
					throw new Exception("Can not create directory: "
							+ genresDir.getAbsolutePath());
				}
			} else {
				throw new Exception("Can not create directory: "
						+ genresDir.getAbsolutePath());
			}
		}
		// Create a directory:
		// web/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser/freegalPIN
		// so it can be accessed as
		// http://vufindUrl/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser/freegalPIN
		File songsDir = new File(genresDir, freegalPIN);
		if (!songsDir.mkdirs()) {
			throw new Exception("Can not create directory: "
					+ songsDir.getAbsolutePath());
		}

		// Create a file:
		// web/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser/index.htm
		// so it can be accessed as
		// http://vufindUrl/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser
		File file = new File(genresDir, "index.html");
		copyStreams(
				this.getClass().getResourceAsStream(
						"/org/vufind/cron/testdata/freegal/genres.xml"),
				new FileOutputStream(file));
		// make a copy of index.html as index.htm in case index.html is not
		// configured on windows
		FileUtils.copyFile(file, new File(genresDir, "index.htm"));

		// Create a file:
		// web/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser/freegalPIN/base64Genre
		// so it can be accessed as
		// http://vufindUrl/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser/freegalPIN/base64Genre
		file = new File(songsDir, Base64Coder.encodeString("Corridos"));
		copyStreams(
				this.getClass().getResourceAsStream(
						"/org/vufind/cron/testdata/freegal/Corridos.xml"),
				new FileOutputStream(file));

		// Create a file:
		// web/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser/freegalPIN/base64Genre
		// so it can be accessed as
		// http://vufindUrl/files/freegal/services/genre/freegalAPIkey/freegalLibrary/freegalUser/freegalPIN/base64Genre
		file = new File(songsDir, Base64Coder.encodeString("Comedy/Spoken"));
		copyStreams(
				this.getClass().getResourceAsStream(
						"/org/vufind/cron/testdata/freegal/ComedySpoken.xml"),
				new FileOutputStream(file));

		// set freegalUrl to point to the URLs of our downloaded data
		processSettings.put("freegalUrl", ini.get("Site", "url")
				+ "/files/freegal");

		// Temporarily set all freegal albums to "deleted" status
		// We keep a special timestamp so we can revert back later in tearDown
		thisRunTimeStamp = (int) (new Date().getTime() / 100) + 36000;
		PreparedStatement setAllFreegalStatusToDeleted = econtentConn
				.prepareStatement("UPDATE econtent_record SET status = 'deleted', date_updated = ? WHERE source = 'freegal' AND status = 'active'");
		setAllFreegalStatusToDeleted.setLong(1, thisRunTimeStamp);
		setAllFreegalStatusToDeleted.executeUpdate();
	}

	@After
	public void tearDown() throws Exception {
		// rollback would not work if the database is MyISAM
		// so we simply delete the records we created if they still exists
		long id = -1;
		if ((id = getEContentRecordId("Mexicanisimo (JUNIT TESTING)",
				"Ignacio López Tarso")) != -1) {
			importFreegal.deleteAlbumAndSongs(econtentConn, id);
		}
		if ((id = getEContentRecordId("Between the Worlds (JUNIT TESTING)",
				"Alkonost")) != -1) {
			importFreegal.deleteAlbumAndSongs(econtentConn, id);
		}
		if ((id = getEContentRecordId("Cuentopos 2 (JUNIT TESTING)",
				"Maria Elena Walsh")) != -1) {
			importFreegal.deleteAlbumAndSongs(econtentConn, id);
		}
		// this record may be created on the fly by one of the tests
		// we need to delete it if it still exists (due to test failures)
		if ((id = getEContentRecordId(ON_THE_FLY_ALBUM_TITLE,
				ON_THE_FLY_ALBUM_AUTHOR)) != -1) {
			importFreegal.deleteAlbumAndSongs(econtentConn, id);
		}

		// set status to "active" for the ones we set to "deleted" in setUp
		PreparedStatement setAllFreegalStatusToActive = econtentConn
				.prepareStatement("UPDATE econtent_record SET status = 'active', date_updated = ? WHERE source = 'freegal' AND status = 'deleted' AND date_updated = ?");
		setAllFreegalStatusToActive.setLong(1,
				(int) (new Date().getTime() / 100));
		setAllFreegalStatusToActive.setLong(2, thisRunTimeStamp);
		setAllFreegalStatusToActive.executeUpdate();

		// rollback database changes --- in case rollback is supported
		vufindConn.rollback();
		econtentConn.rollback();

		// remove temporary files
		FileUtils.deleteDirectory(new File(ini.get("Site", "local")
				+ "/files/freegal"));

		// rollback solr updates
		Util.doSolrUpdate(ini.get("Index", "url") + "/econtent", "<rollback/>");

		// add some info to the log so we can see
		logger.info("End of tearDown() - eContent records count = "
				+ countEContentRecords());
		logger.info("End of tearDown() - eContent items count = "
				+ countEContentItems());

	}

	@Test
	public void testImportFreegal() {
		long activeFreegalAlbumCount = 0;
		long eContentCount = 0;
		long eContentItemCount = 0;

		// pre-import checks
		try {
			// before the ImportFreegal process, the albums should not exists
			assertEquals(
					"Album: \"Mexicanisimo (JUNIT TESTING) - Ignacio López Tarso\" should not exist.",
					-1,
					getEContentRecordId("Mexicanisimo (JUNIT TESTING)",
							"Ignacio López Tarso"));
			assertEquals(
					"Album: \"Between the Worlds (JUNIT TESTING) - Alkonost\" should not exist.",
					-1,
					getEContentRecordId("Between the Worlds (JUNIT TESTING)",
							"Alkonost"));
			assertEquals(
					"Album: \"Cuentopos 2 (JUNIT TESTING) - Maria Elena Walsh\" should not exist.",
					-1,
					getEContentRecordId("Cuentopos 2 (JUNIT TESTING)",
							"Maria Elena Walsh"));

			// get total count of eContent records before the import process
			eContentCount = countEContentRecords();

			// get total count of eContent items before the import process
			eContentItemCount = countEContentItems();

			// get total count of active Freegal albums before the import
			// process
			activeFreegalAlbumCount = countActiveFreegalAlbums();
		} catch (Exception e) {
			fail(e.toString());
		}

		// run the ImportFreegal process
		importFreegal.doCronProcess(serverName, ini, processSettings,
				vufindConn, econtentConn, cronEntry, logger);

		// post-import checks
		try {
			// after the ImportFreegal process, the albums SHOULD exists
			assertFalse(
					"Album: \"Mexicanisimo (JUNIT TESTING) - Ignacio López Tarso\" SHOULD exist.",
					getEContentRecordId("Mexicanisimo (JUNIT TESTING)",
							"Ignacio López Tarso") == -1);
			assertFalse(
					"Album: \"Between the Worlds (JUNIT TESTING) - Alkonost\" SHOULD exist.",
					getEContentRecordId("Between the Worlds (JUNIT TESTING)",
							"Alkonost") == -1);
			assertFalse(
					"Album: \"Cuentopos 2 (JUNIT TESTING) - Maria Elena Walsh\" SHOULD exist.",
					getEContentRecordId("Cuentopos 2 (JUNIT TESTING)",
							"Maria Elena Walsh") == -1);

			// after the import process, there should be 3 more econtent records
			assertEquals(
					"There should be 3 more econtent records after the import",
					eContentCount + 3, countEContentRecords());
			// after the import process, there should be 14 more econtent items
			assertEquals(
					"There should be 14 more econtent items after the import",
					eContentItemCount + 14, countEContentItems());

			// after the import process, there should be 3 more ACTIVE albums
			assertEquals(
					"There should be 3 more active albums after the import",
					activeFreegalAlbumCount + 3, countActiveFreegalAlbums());

		} catch (Exception e) {
			fail(e.toString());
		}
	}

	@Test
	public void testDeleteAlbumsNotInFreegalAPI() {
		long activeFreegalAlbumCount = 0;
		long eContentCount = 0;
		long eContentItemCount = 0;
		// pre-import checks
		try {
			// the test record should not exists
			assertEquals(
					"Album: \""
							+ ON_THE_FLY_ALBUM_TITLE
							+ "\" should not exist before the ImportFreegal process.",
					-1,
					getEContentRecordId(ON_THE_FLY_ALBUM_TITLE,
							ON_THE_FLY_ALBUM_AUTHOR));

			// now we create the album on the fly
			createTestAlbum();

			// now the test album should exist and is ACTIVE
			assertTrue(
					"Album: \""
							+ ON_THE_FLY_ALBUM_TITLE
							+ "\" SHOULD exist before the ImportFreegal process.",
					getActiveAlbumId(ON_THE_FLY_ALBUM_TITLE,
							ON_THE_FLY_ALBUM_AUTHOR) != -1);

			// get total count of eContent records before the import process
			eContentCount = countEContentRecords();

			// get total count of eContent items before the import process
			eContentItemCount = countEContentItems();

			// get total count of active Freegal albums before the import
			// process
			activeFreegalAlbumCount = countActiveFreegalAlbums();
		} catch (Exception e) {
			fail(e.toString());
		}

		// run the ImportFreegal process
		importFreegal.doCronProcess(serverName, ini, processSettings,
				vufindConn, econtentConn, cronEntry, logger);

		// post-import checks
		try {
			// the album created on the fly should not be active
			assertEquals(
					"Album: \""
							+ ON_THE_FLY_ALBUM_TITLE
							+ "\" should NOT be active AFTER the ImportFreegal process.",
					-1,
					getActiveAlbumId(ON_THE_FLY_ALBUM_TITLE,
							ON_THE_FLY_ALBUM_AUTHOR));
			// It should not even exist at all
			assertEquals(
					"Album: \""
							+ ON_THE_FLY_ALBUM_TITLE
							+ "\" should NOT exist after the ImportFreegal process.",
					-1,
					getEContentRecordId(ON_THE_FLY_ALBUM_TITLE,
							ON_THE_FLY_ALBUM_AUTHOR));

			// after the import process, there should be 14 more econtent items
			assertEquals(
					"There should be 14 more econtent items after the import",
					eContentItemCount + 14, countEContentItems());

			// after the import process, there should be 2 more ACTIVE albums
			// because 1 active (one we created on the fly) should have been
			// deleted by the import process, and 3 new albums
			// should have been imported by the import process, so the NET
			// count should be 2 more active albums
			assertEquals(
					"There should be 2 more active albums after the import",
					activeFreegalAlbumCount + 2, countActiveFreegalAlbums());
		} catch (Exception e) {
			fail(e.toString());
		}
	}

	@Test
	public void testDeleteAlbumsAndSongsFromDatabase() {
		long testAlbumId = -1;
		try {
			// the test record should not exists before we created it
			assertEquals(
					"Album: \"" + ON_THE_FLY_ALBUM_TITLE
							+ "\" should not exist before we created it.",
					-1,
					getEContentRecordId(ON_THE_FLY_ALBUM_TITLE,
							ON_THE_FLY_ALBUM_AUTHOR));

			// now we create the album on the fly
			testAlbumId = createTestAlbum();

			// now the test album should exist and is ACTIVE
			assertTrue(
					"Album: \"" + ON_THE_FLY_ALBUM_TITLE
							+ "\" SHOULD exist after we created it.",
					getActiveAlbumId(ON_THE_FLY_ALBUM_TITLE,
							ON_THE_FLY_ALBUM_AUTHOR) != -1);

			// now we delete it
			importFreegal.deleteAlbumAndSongs(econtentConn, testAlbumId);

			// the test record should not exists
			assertEquals(
					"Album: \"" + ON_THE_FLY_ALBUM_TITLE
							+ "\" should not exist AFTER we deleted it.",
					-1,
					getEContentRecordId(ON_THE_FLY_ALBUM_TITLE,
							ON_THE_FLY_ALBUM_AUTHOR));
		} catch (Exception e) {
			fail(e.toString());
		}
	}

	private void copyStreams(InputStream in, OutputStream out)
			throws IOException {
		byte[] buf = new byte[1024];
		int len;
		while ((len = in.read(buf)) > 0) {
			out.write(buf, 0, len);
		}
		in.close();
		out.close();
	}

	private long getEContentRecordId(String title, String author)
			throws SQLException {
		PreparedStatement stmt = econtentConn
				.prepareStatement("SELECT id FROM econtent_record WHERE title = ? AND author = ?");
		stmt.setString(1, title);
		stmt.setString(2, author);
		ResultSet results = stmt.executeQuery();
		long id = -1;
		if (results.next()) {
			id = results.getLong("id");
		}
		results.close();
		stmt.close();
		return id;
	}

	private long getActiveAlbumId(String title, String author)
			throws SQLException {
		PreparedStatement stmt = econtentConn
				.prepareStatement("SELECT id FROM econtent_record WHERE source='freegal' AND status='active' AND title = ? AND author = ?");
		stmt.setString(1, title);
		stmt.setString(2, author);
		ResultSet results = stmt.executeQuery();
		long id = -1;
		if (results.next()) {
			id = results.getLong("id");
		}
		results.close();
		stmt.close();
		return id;
	}

	private long countEContentRecords() throws SQLException {
		PreparedStatement stmt = econtentConn
				.prepareStatement("SELECT COUNT(id) FROM econtent_record");
		ResultSet results = stmt.executeQuery();
		long count = 0;
		if (results.next()) {
			count = results.getLong(1);
		}
		results.close();
		stmt.close();
		return count;
	}

	private long countActiveFreegalAlbums() throws SQLException {
		PreparedStatement stmt = econtentConn
				.prepareStatement("SELECT COUNT(id) FROM econtent_record WHERE source='freegal' AND status='active'");
		ResultSet results = stmt.executeQuery();
		long count = 0;
		if (results.next()) {
			count = results.getLong(1);
		}
		results.close();
		stmt.close();
		return count;
	}

	private long countEContentItems() throws SQLException {
		PreparedStatement stmt = econtentConn
				.prepareStatement("SELECT COUNT(id) FROM econtent_item");
		ResultSet results = stmt.executeQuery();
		long count = 0;
		if (results.next()) {
			count = results.getLong(1);
		}
		results.close();
		stmt.close();
		return count;
	}

	private long createTestAlbum() throws SQLException {
		long createdAlbumId = -1;
		PreparedStatement addAlbumToDatabase = econtentConn
				.prepareStatement(
						"INSERT INTO econtent_record (title, author, source, status, date_added) VALUES (?, ?, 'Freegal', 'active', ?)",
						PreparedStatement.RETURN_GENERATED_KEYS);
		addAlbumToDatabase.setString(1, ON_THE_FLY_ALBUM_TITLE);
		addAlbumToDatabase.setString(2, ON_THE_FLY_ALBUM_AUTHOR);
		addAlbumToDatabase.setLong(3, (int) (new Date().getTime() / 100));
		addAlbumToDatabase.executeUpdate();
		ResultSet generatedKeys = addAlbumToDatabase.getGeneratedKeys();
		if (generatedKeys.next()) {
			createdAlbumId = generatedKeys.getLong(1);
		}
		generatedKeys.close();
		addAlbumToDatabase.close();
		return createdAlbumId;
	}
}
