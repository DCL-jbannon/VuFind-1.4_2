package db;

import java.sql.SQLException;

public abstract class BaseDBEcontentTests extends BaseDBTests
{
	public String getDatabaseName() 
	{
		return "testdclecontent";
	}
	
	public String getConnectionString()
	{
		return "jdbc:mysql://localhost/?user=root&password=&useUnicode=yes&characterEncoding=UTF-8";
	}
	
	public String getSQLFileName()
	{
		return "C:/projects/VuFind-Plus/test/sql/testEcontent.sql";
	}	
}