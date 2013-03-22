package org.dcl.file;
import org.dcl.file.FindFile;
import org.junit.Before;

abstract public class BaseFindFileTests
{
	protected FindFile service;
	protected String path = "C:/projects/VuFind-Plus/vufind/cron/fileTests";
	protected String extEPUB = "epub";
	protected String extPDF = "pdf";
	protected String extPNG = "png";
	protected String extJPG = "jpg";
	
	@Before
	public void setUp() throws Exception 
	{
		this.service = new FindFile();
	}
}