package org.dcl.Utils;

import static org.junit.Assert.*;

import java.util.Arrays;
import java.util.Collection;

import org.dcl.Utils.ISBNUtils;
import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.junit.runners.Parameterized;
import org.junit.runners.Parameterized.Parameters;

@RunWith(value = Parameterized.class)
public class ISBNUtilsTests
{

	private String expected;
	private String text;
	private ISBNUtils service;
	
	@Before
	public void setUp() throws Exception 
	{
		this.service = new ISBNUtils();
	}
	
	public ISBNUtilsTests(String text, String expected)
	{
		this.text = text;
		this.expected = expected;
	}
	
	@Test
	public void detectGetISBN()
	{
		String actual = this.service.detectGetISBN(this.text);
		assertEquals(this.expected, actual);
	}
	
	@Parameters
	 public static Collection<Object[]> data()
	 {
	   Object[][] data = new Object[][]{	{ "1569764301 (ebook)9780982286456 (ebook)", "9780982286456"},
			   								{ "9780761388623\n0761388621", "9780761388623"},
			   								{ "9781414348445 (ebook)", "9781414348445"},
			   								{ "9781604862850 LON", "9781604862850"},
			   								{ "97816 LON", ""},
			   								{ "", ""},
			   								{ null, ""},
			   							};
	   return Arrays.asList(data);
	 }

}
