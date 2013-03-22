package db;
import java.sql.DriverManager;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import org.apache.commons.io.IOUtils;
import com.mysql.jdbc.PreparedStatement;

public abstract class BaseDBTests
{
    protected static Connection conn = null;
	public abstract String getDatabaseName();
	public abstract String getConnectionString();
	public abstract String getSQLFileName();
	
	/***
	 * NO COMMMENTS ALLOWED
	 */
	public BaseDBTests()
	{
		if (BaseDBTests.conn == null)
		{
			java.sql.PreparedStatement stmt = null;
			try
			{
				BaseDBTests.conn = DriverManager.getConnection(this.getConnectionString());
				stmt = conn.prepareStatement("CREATE DATABASE IF NOT EXISTS " + this.getDatabaseName());
				stmt.execute();
				BaseDBTests.conn.setCatalog(this.getDatabaseName());
				
				/*
				 * http://www.java-examples.com/java-string-split-example
				 * Let's create the tables. Only structure
				 */
				String query = IOUtils.toString(new FileReader(this.getSQLFileName()));
				String[] querySplited = query.split(";");
				
				for(int i =0; i < querySplited.length ; i++)
				{
					stmt = BaseDBTests.conn.prepareStatement(querySplited[i]);
					stmt.execute();
				}
				stmt.close();
			}
			catch (Exception e) 
			{
				System.out.println("exception type: " + e.getClass() + " Exception Message: " + e.getMessage());
				System.exit(0);
			}
		}
	}
}