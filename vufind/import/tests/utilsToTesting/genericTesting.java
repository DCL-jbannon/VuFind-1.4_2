package utilsToTesting;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Iterator;
import org.API.OverDrive.OverDriveCollectionIterator;
import org.econtent.PopulateSolrOverDriveAPIItems;
import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.junit.Test;
import org.solr.SolrWrapper;

public class genericTesting
{
	@Test
	public void test1() throws SQLException
	{
		SolrWrapper solrWrapper = new SolrWrapper("localhost:8080/solr/testEcontent");
		OverDriveCollectionIterator odci = new OverDriveCollectionIterator("DouglasCL", "aha4lf0c2opJGfaRHxtkIEajvb3x2YKV", 1344);
		String databaseConnectionInfo = "jdbc:mysql://localhost/dclecontent?user=root&password=&useUnicode=yes&characterEncoding=UTF-8";
		Connection conn = DriverManager.getConnection(databaseConnectionInfo);		
		PopulateSolrOverDriveAPIItems service = new PopulateSolrOverDriveAPIItems(odci, conn, solrWrapper);
		service.execute();
	}
	
	//@Test
	public void test()
	{
		OverDriveCollectionIterator test = new OverDriveCollectionIterator("DouglasCL", "aha4lf0c2opJGfaRHxtkIEajvb3x2YKV", 1344);
		int i = 1;
		while (test.hasNext() && i<2)
		{
			JSONObject result = test.next();
			JSONArray a = (JSONArray) result.get("products");
			Iterator b = a.iterator();
			while(b.hasNext())
			{
				JSONObject c = (JSONObject) b.next();
				System.out.println(c.toJSONString());
			}
			i++;
		}
	}
}