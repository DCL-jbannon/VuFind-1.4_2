<?php
class ResultsThreeMAPIMother
{
	
	const holdLength = 895645;
	
	public function getItemDetails($totalCopies = 1, $availableCopies = 1, 
								   $canCheckout = "TRUE",
								   $canBeHold = "TRUE")
	{
		$xml  = "<Item xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">";
		$xml .= "<ItemId>ff3r9</ItemId><Title>aDummyTitle</Title><SubTitle /><Authors>Hoag, Tami</Authors><Description>aDummyDescription</Description>";
		$xml .= "<Publisher>Random House Publishing Group</Publisher><PubDate>1235001600000</PubDate><PubYear>2009</PubYear>";
		$xml .= "<Size>1.9 MB</Size>";
		$xml .= "<Language>en</Language><PhysicalISBN>9780553560503</PhysicalISBN><ISBN13>9780307497697</ISBN13><BookFormat>EPUB</BookFormat>";
		$xml .= "<NumberOfPages>256</NumberOfPages><CanCheckout>".$canCheckout."</CanCheckout><CanHold>".$canBeHold."</CanHold><OnHold>FALSE</OnHold>";
		$xml .= "<TotalCopies>".$totalCopies."</TotalCopies><AvailableCopies>".$availableCopies."</AvailableCopies><OnHoldCount>".self::holdLength."</OnHoldCount>";
		$xml .= "<BookLinkURL>http://ebook.3m.com/library/-document_id-ff3r9</BookLinkURL>";
		$xml .= "<CoverLinkURL>http://ebook.3m.com/delivery/img?type=DOCUMENTIMAGE&amp;amp;documentID=ff3r9&amp;amp;token=nobody</CoverLinkURL>";
		$xml .= "</Item>";
		return simplexml_load_string($xml);
	}
	
	public function getItemsDetails()
	{
		$xml = '<?xml version="1.0"?>
				<ArrayOfItem xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
					<Item>
						<ItemId>6pez9</ItemId><Title>Go Baby Go</Title><SubTitle/><Authors>Janovitz, Marilyn</Authors><Description>Praise for Baby Baby Baby! &amp;quot;Reflects babies&amp;apos; everyday activities with verve and joy.&amp;quot; -Booklist In this companion book to Baby Baby Baby!, fidgety baby is back and ventures outdoors for fun with her two favorite pets. Baby&amp;apos;s jumping high Baby&amp;apos;s jumping low Baby&amp;apos;s flying in the air Go  Go  Go! Entertaining and lively text coupled with adorable and engaging art by author/illustrator Marilyn Janovitz makes this the perfect book for babies and the person who reads to them!  Baby&amp;apos;s jumping high, Baby&amp;apos;s bouncing low Baby&amp;apos;s flying in the air Go Go Go!</Description><Publisher>Sourcebooks</Publisher><PubDate>1314835200000</PubDate><PubYear>2011</PubYear><Size>3.9 MB</Size><Language>en</Language><PhysicalISBN/><ISBN13>9781402259388</ISBN13><BookFormat>EPUB</BookFormat><NumberOfPages>24</NumberOfPages><CanCheckout>TRUE</CanCheckout><CanHold>FALSE</CanHold><OnHold>FALSE</OnHold><TotalCopies>1</TotalCopies><AvailableCopies>1</AvailableCopies><OnHoldCount>0</OnHoldCount><BookLinkURL>http://ebook.3m.com/library/-document_id-6pez9</BookLinkURL><CoverLinkURL>http://ebook.3m.com/delivery/img?type=DOCUMENTIMAGE&amp;amp;documentID=6pez9&amp;amp;token=nobody</CoverLinkURL>
					</Item>
					<Item>
						<ItemId>gn9r8</ItemId><Title>Beyond a House Divided</Title><SubTitle>The Moral Consensus Ignored by Washington, Wall Street, and the Media</SubTitle><Authors>Anderson, Carl</Authors><Description>If you follow politics or the news, America is a country of culture wars and great divides, a partisan place of red states and blue states, of us against them. From pundits to politicians it seems that anyone with an audience sees a polarized country - a country at war with itself.In a radical departure from this &amp;quot;conventional wisdom,&amp;quot; Carl Anderson explores what the talking heads have missed: an overwhelming American consensus on many of the country&amp;apos;s seemingly most divisive issues. If the debates are shrill in public, he says, there is a quiet consensus in private - one that America&amp;apos;s institutions ignore at their peril. From health care, to the role of religion in America, to abortion, to the importance of traditional ethics in business and society, Anderson uses fresh polling data and keen insight in BEYOND A HOUSE DIVIDEDto show that a surprising consensus has emerged despite these debates. He sheds light on what&amp;apos;s been missing in the public and political debates of the last several years: the consensus that isn&amp;apos;t hard to find if you know where to look.For Anderson, allowing polar opposites to drive the discussion has made the resolution of contentious issues impossible. Instead, he says, we should look to the consensus among Americans as the best prospect for a beneficial conclusion.From the Trade Paperback edition.</Description><Publisher>The Doubleday Religious Publishing Group</Publisher><PubDate>1288656000000</PubDate><PubYear>2010</PubYear><Size>2.8 MB</Size><Language>en</Language><PhysicalISBN>9780307887740</PhysicalISBN><ISBN13>9780307887757</ISBN13><BookFormat>EPUB</BookFormat><NumberOfPages>128</NumberOfPages><CanCheckout>FALSE</CanCheckout><CanHold>FALSE</CanHold><OnHold>FALSE</OnHold><TotalCopies>0</TotalCopies><AvailableCopies>0</AvailableCopies><OnHoldCount>0</OnHoldCount><BookLinkURL>http://ebook.3m.com/library/-document_id-gn9r8</BookLinkURL><CoverLinkURL>http://ebook.3m.com/delivery/img?type=DOCUMENTIMAGE&amp;amp;documentID=gn9r8&amp;amp;token=nobody</CoverLinkURL>
					</Item>
				</ArrayOfItem>';
		return simplexml_load_string($xml);
	}
	
	
	
	public function getItemCanBeCheckOut()
	{
		return $this->getItemDetails(1,1,"TRUE");
	}
	
	public function getItemCannotBeCheckOut()
	{
		return $this->getItemDetails(1,1,"FALSE");
	}
	
	public function getItemCanBeHold()
	{
		return $this->getItemDetails(1,1,"", "TRUE");
	}
	
	public function getItemCannotBeHold()
	{
		return $this->getItemDetails(1,1,"", "FALSE");
	}
	
	public function getPatronCirculationResults($itemId = 1234, $holds = true, $checkout = true)
	{
		$xml  = "<PatronCirculation xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">";
		$xml .= "<PatronId>23025006182976</PatronId>";
		if($holds)
		{
			$xml .= "<Holds>";
			$xml .= "<Item>";
			$xml .= "<ItemId>".$itemId."</ItemId><ISBN13>1234567890123</ISBN13>";
			$xml .= "<EventStartDateInUTC>2012-05-23T13:23:34</EventStartDateInUTC>";
			$xml .= "<EventEndDateInUTC>2012-05-29T14:25:39</EventEndDateInUTC>";
			$xml .= "</Item>";
			$xml .= "</Holds>";
		}
		
		if($checkout)
		{
			$xml .= "<Checkouts>";
			$xml .= "<Item>";
			$xml .= "	<ItemId>".$itemId."</ItemId><ISBN13>1234567890123</ISBN13>";
			$xml .= "	<EventStartDateInUTC>2012-05-23T13:23:34</EventStartDateInUTC>";
			$xml .= "	<EventEndDateInUTC>2012-05-29T14:25:39</EventEndDateInUTC>";
			$xml .= "</Item>";
			$xml .= "<Item>";
			$xml .= "	<ItemId>".$itemId."_2</ItemId><ISBN13>1234567890123</ISBN13>";
			$xml .= "	<EventStartDateInUTC>2012-05-23T13:23:34</EventStartDateInUTC>";
			$xml .= "	<EventEndDateInUTC>2012-05-29T14:25:39</EventEndDateInUTC>";
			$xml .= "</Item>";
			$xml .= "</Checkouts>";
		}
		
		$xml .= "<Reserves /></PatronCirculation>";
		return simplexml_load_string($xml);
	}
	
	public function getPatronCirculationResultsNoHoldsDone($itemIdHold)
	{
		return $this->getPatronCirculationResults($itemIdHold, false);
	}
	
	public function checkOutSuccessfull()
	{
		$xml ='<?xml version="1.0"?>
				<CheckoutResult xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
				<ItemId>6pez9</ItemId>
				<DueDateInUTC>2013-01-07T22:51:02</DueDateInUTC></CheckoutResult>';
		return simplexml_load_string($xml);
	}
	
	public function placeHoldSuccessfull()
	{
		$xml ='<?xml version="1.0"?>
		<PlaceHoldResult xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		<ItemId>6pez9</ItemId>
		<AvailabilityDateInUTC>2013-01-07T22:51:02</AvailabilityDateInUTC></PlaceHoldResult>';
		return simplexml_load_string($xml);
	}
	
	
	public function getErrorMessage()
	{
		$xml = '<?xml version="1.0"?>
				<Error xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
				<Code>Gen-001</Code><Message>aDummyMessage</Message>
				</Error>';
		return simplexml_load_string($xml);
	}
	
	public function getItemCirculationResult()
	{
		$xml = '<?xml version="1.0"?>
				<ItemCirculation xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
				<ItemId>6pez9</ItemId><ISBN13>9781402259388</ISBN13><TotalCopies>1</TotalCopies><AvailableCopies>1</AvailableCopies><Checkouts/><Holds/><Reserves/>
				</ItemCirculation>';
		return simplexml_load_string($xml);
	}
	
	public function getItemsCirculationResult()
	{
		$xml = '<?xml version="1.0"?>
		<ArrayOfItemCirculation xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		<ItemCirculation>
		<ItemId>gn9r9</ItemId><ISBN13>9780307887955</ISBN13><TotalCopies>1</TotalCopies><AvailableCopies>0</AvailableCopies><Checkouts/><Holds><Patron><PatronId>23025003528973</PatronId><EventStartDateInUTC>2012-12-10T17:23:24</EventStartDateInUTC></Patron><Patron><PatronId>23025005098306</PatronId><EventStartDateInUTC>2012-12-16T04:39:27</EventStartDateInUTC></Patron></Holds><Reserves><Patron><PatronId>23025006504005</PatronId><EventStartDateInUTC>2012-12-13T03:15:11</EventStartDateInUTC><EventEndDateInUTC>2012-12-19T03:15:11</EventEndDateInUTC></Patron></Reserves>
		</ItemCirculation>
		<ItemCirculation>
		<ItemId>6pez9</ItemId><ISBN13>9781402259388</ISBN13><TotalCopies>1</TotalCopies><AvailableCopies>1</AvailableCopies><Checkouts/><Holds/><Reserves/>
		</ItemCirculation>
		</ArrayOfItemCirculation>';
		return simplexml_load_string($xml);
	}
	
}
?>