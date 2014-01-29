<?php

require_once dirname(__FILE__).'/../../Utils/RegularExpressions.php';

interface IOverDriveSSDOMXPath{}

class OverDriveSSDOMXPath implements IOverDriveSSDOMXPath
{
	private $regularExpressions;
	
	public function __construct()
	{
		$this->regularExpressions = new RegularExpressions();	
	}
	
	public function getUrlHTML302($source)
	{
		$doc = $this->loadDOMByHTML($source);
		$xpath = $this->getDOMXPath($doc);
		
		$query = "/html/body/h2/a/@href";
		$entries = $xpath->query($query);
		if(isset($entries->item(0)->value))
		{
			return $entries->item(0)->value;
		}
		return '';
	}
	
	public function getTransactionIdByItemID($source, $itemId) 
	{
		$transactionId = "";
		$doc = $this->loadDOMByHTML($source);
		$xpath = $this->getDOMXPath($doc);
		
		$query = '//div[@class="dwnld-container"]';
		$entries = $xpath->query($query);
		foreach ($entries as $entry)
		{
			/* @var $entry DOMElement */
			if(preg_match("/".$itemId."/i", $entry->getAttribute("data-reserveid")))
			{
				$transactionId = $entry->getAttribute("data-transaction");
			}
		}
		return $transactionId;
	}
	
	public function getLendingOptionsValue($source)
	{
		$doc = $this->loadDOMByHTML($source);
		$xpath = $this->getDOMXPath($doc);
		
		$result = new stdClass();
		$result->ebook  = $this->getDaysLending("class_1_preflendingperiod", $xpath);
		$result->audio  = $this->getDaysLending("class_2_preflendingperiod", $xpath);
		$result->video  = $this->getDaysLending("class_4_preflendingperiod", $xpath);
		$result->disney = $this->getDaysLending("class_5_preflendingperiod", $xpath);
		
		return $result;
	}
	
	public function getPatronCirculation($source, $baseUrl)
	{
		
		$result = new stdClass();
		$result->Checkouts = array();
		$result->Holds = array();
		$result->AvailableHolds = array();
		$result->WishList = array();
		
		$doc = $this->loadDOMByHTML($source);
		$xpath = $this->getDOMXPath($doc);
		
		//checkOuts from overdrive
		$query = ".//*[@id='bookshelfBlockGrid']";
		$entries = $xpath->query($query);
		if($entries->length > 0 || $entries != NULL)
		{
			$i = 1;
			foreach($entries->item(0)->childNodes as $elementList)
			{
				if(get_class($elementList) == 'DOMElement')
				{
					$query = ".//*[@id='bookshelfBlockGrid']/li[".$i."]/div[2]/div[2]/a/@href";
					$attr = $xpath->query($query);
					if($attr->item(0) != NULL)
					{
					  $result->Checkouts[$i-1]['ItemId'] = strtoupper($this->regularExpressions->getFieldValueFromURL($attr->item(0)->value, "ID"));
					}
					
					$query = ".//*[@id='bookshelfBlockGrid']/li[".$i."]/div[2]/div[2]/a/@title";
					$attr = $xpath->query($query);
					if($attr->item(0) != NULL)
					{
					  $result->Checkouts[$i-1]['Title'] = $attr->item(0)->value;
					}
					$query = ".//*[@id='bookshelfBlockGrid']/li[".$i."]/div[3]/div[5]/noscript";
					$attr = $xpath->query($query);
					if($attr->item(0) != NULL)
					{
					  $result->Checkouts[$i-1]['Expires'] = $attr->item(0)->childNodes->item(0)->wholeText;
					}
					$query = ".//*[@id='bookshelfBlockGrid']/li[".$i."]/div[3]/div/div/ul";
					$attr = $xpath->query($query);
					if($attr->item(0) != NULL)
					{

					$result->Checkouts[$i-1]['Link'] = "";
					$result->Checkouts[$i-1]['OverDriveReadLink'] = false;
					$downIndex = 1;
					$hasBeenDownloaded = false;
					$linkToAccessEcontent = "";
					$fulfillODReadLink = false;
					foreach ($attr->item(0)->childNodes as $downElement)
					{
						if( isset($downElement->tagName) && ($downElement->tagName == 'li') )
						{
							if($downElement->getAttribute('data-lckd') == 1)
							{
								$queryLink = ".//*[@id='bookshelfBlockGrid']/li[".$i."]/div[3]/div/div/ul/noscript[".$downIndex."]/div/a/@href";
								$attrLink = $xpath->query($queryLink);
								if(isset($attrLink->item(0)->value))
								{
									$linkToAccessEcontent = $baseUrl.$attrLink->item(0)->value;
									$result->Checkouts[$i-1]['Link'] = $baseUrl.$attrLink->item(0)->value;
									$hasBeenDownloaded = true;
									if($fulfillODReadLink)
									{
										$result->Checkouts[$i-1]['OverDriveReadLink'] = preg_replace("/FormatID=([0-9]{3}|[0-9]{2})/","FormatID=610", $result->Checkouts[$i-1]['Link']);
									}
								}
							}
							$downIndex++;
							
							if($downElement->getAttribute('value') == 610)
							{
								if(!empty($result->Checkouts[$i-1]['Link']))
								{
									$result->Checkouts[$i-1]['OverDriveReadLink'] = preg_replace("/FormatID=[0-9]{3}/","FormatID=610", $result->Checkouts[$i-1]['Link']);
								}
								else
								{
									$fulfillODReadLink = true;
								}
							}
						}
						
					}
				}
					//OverDrive Read??
					$query = ".//*[@id='bookshelfBlockGrid']/li[".$i."]/div[3]/div[3]/a/@href";
					$attr = $xpath->query($query);
					if($attr->item(0) != NULL)
					{
						$result->Checkouts[$i-1]['OverDriveReadLink'] = $baseUrl.$attr->item(0)->value;
					}
					
					$result->Checkouts[$i-1]['ChooseFormat'] = !$hasBeenDownloaded;
					
					$i++;
				}
			}
		}
		
		//Holds
		$query = '//*[@id="myAccount2Tab"]/div[3]/div/ul';
		$entries = $xpath->query($query);
		
		if($entries->length > 0)
		{
			$i = 1;
			$indexHolds = 0;
			$indexAvalHolds = 0;
			foreach($entries->item(0)->childNodes as $elementList)
			{
				if(isset($elementList->tagName) && ($elementList->tagName == "li"))
				{
					$query = './/*[@id=\'myAccount2Tab\']/div[3]/div/ul/li['.$i.']/div[2]/ul/li[1]';
					$attr = $xpath->query($query);
					if ($attr->item(0)->hasAttribute('class') && $attr->item(0)->getAttribute('class') == 'holds-borrow-link')
					{
						//Available Holds
						$query = '//*[@id="myAccount2Tab"]/div[3]/div/ul/li['.$i.']/div/div[2]/a/@href';
						$attr = $xpath->query($query);
						$result->AvailableHolds[$indexAvalHolds]['ItemId'] = strtoupper($this->regularExpressions->getFieldValueFromURL($attr->item(0)->value, "ID"));
						
						$query = '//*[@id="myAccount2Tab"]/div[3]/div/ul/li['.$i.']/div/div[2]/a/@title';
						$attr = $xpath->query($query);
						$result->AvailableHolds[$indexAvalHolds]['Title'] = $attr->item(0)->value;
						
						$indexAvalHolds++;
					}
					else
					{
						//Unavailable Holds
						$query = '//*[@id="myAccount2Tab"]/div[3]/div/ul/li['.$i.']/div/div[2]/a/@href';
						$attr = $xpath->query($query);
						$result->Holds[$indexHolds]['ItemId'] = strtoupper($this->regularExpressions->getFieldValueFromURL($attr->item(0)->value, "ID"));
						
						$query = ".//*[@id='myAccount2Tab']/div[3]/div/ul/li[".$i."]/div[2]/ul/li[1]/a/@href";
						$attr = $xpath->query($query);
						$urlWithFormat = str_replace(chr(13).chr(10), "", $attr->item(0)->value);
						$result->Holds[$indexHolds]['FormatId'] = $this->regularExpressions->getFieldValueFromURL($urlWithFormat, "format");
						
						$query = '//*[@id="myAccount2Tab"]/div[3]/div/ul/li['.$i.']/div/div[2]/a/@title';
						$attr = $xpath->query($query);
						$result->Holds[$indexHolds]['Title'] = $attr->item(0)->value;
						
						$query = '//*[@id="myAccount2Tab"]/div[3]/div/ul/li['.$i.']/div[2]/h6[1]/b[1]';
						$attr = $xpath->query($query);
						$result->Holds[$indexHolds]['UserPosition'] = $attr->item(0)->childNodes->item(0)->wholeText;
						
						$query = '//*[@id="myAccount2Tab"]/div[3]/div/ul/li['.$i.']/div[2]/h6[1]/b[2]';
						$attr = $xpath->query($query);
						$totalQueue = $attr->item(0)->childNodes->item(0)->wholeText;
						$result->Holds[$indexHolds]['QueueLength'] = $attr->item(0)->childNodes->item(0)->wholeText;
						
						$indexHolds++;
					}
					
					$i++;
				}
			}
		}
		
		//WishList
		$query = ".//*[@id='wishlistFilter']"; 
		$entries = $xpath->query($query);
		
		if($entries->length > 0)
		{
			$i = 1;
			foreach($entries->item(0)->childNodes as $elementList)
			{			
				$query = ".//*[@id='wishlistFilter']/li[".$i."]/div[2]/a/@title";
				$attr = $xpath->query($query);
				$result->WishList[$i-1]['Title'] = $attr->item(0)->value;
				
				$query = ".//*[@id='wishlistFilter']/li[".$i."]/div[2]/a/@href";
				$attr = $xpath->query($query);
				$result->WishList[$i-1]['ItemId'] = strtoupper($this->regularExpressions->getFieldValueFromURL($attr->item(0)->value, "ID"));
				
				$i++;
			}
		}
		return $result;
	}
	
	public function getItemDetails($source, $itemId)
	{
		$result = new stdClass();
		$doc = $this->loadDOMByHTML($source);
		$xpath = $this->getDOMXPath($doc);
		
		$query = '//*[@id="copiesExpand"]/ul';
		$entries = $xpath->query($query);
	if($entries->item(0) != NULL)
	{
		$elements = $entries->item(0)->childNodes;
		$i=1;
		foreach ($elements as $element)
		{
			if(get_class($element) === "DOMElement")
			{
				/* @var $element DOMElement */
				if($element->tagName == 'li')
				{
					$query = $element->getNodePath()."/div/div[2]/span";
					$text = $xpath->query($query);
					
					switch($i)
					{
						case 1:
							$result->AvailableCopies = $text->item(0)->textContent;
							break;
						case 2:
							$result->TotalCopies = $text->item(0)->textContent;
							break;
						default:
							$query = $element->getNodePath()."/div/div";
							$text = $xpath->query($query);
							$result->OnHoldCount = intval($text->item(0)->textContent);
							break;		
					}
					$i++;
				}
			}
		}
	}
		
		//CAN PLACE HOLD OR BORROW
		
		$result->CanCheckout = false;
		$result->CanHold = false;
		$query = "//a";
		$links = $xpath->query($query);
		foreach($links as $link)
		{
			if($link->childNodes->length > 0)
			{			
				if($link->childNodes->item(0)->textContent == "Place a Hold")
				{
					$result->CanHold = true;
					$query = "//*[contains(@class, 'details-title-button')]/@href";
					$attrLinktoPlaceHold = $xpath->query($query);
					
					foreach($attrLinktoPlaceHold->item(0)->childNodes as $buttonElement)
					{
						if(get_class($buttonElement) == "DOMText")
						{
							$result->formatIdHold = $this->regularExpressions->getFieldValueFromURL($buttonElement->wholeText, "format");
						}
					}
				}
				if($link->childNodes->item(0)->textContent == "Borrow")
				{
					$result->CanCheckout = true;
				}
			}
		}
		
		//WishList
		$query = "//*[@id='wishListButton']";
		$entry = $xpath->query($query);
		$result->CanAddWishList = ($entry->length == 1 ? true : false);
			
		return $result;
	}
	
	
	//private
	private function getDaysLending($type, $xpath)
	{
		$query = '//select[@name="'.$type.'"]';
		$entries = $xpath->query($query);
		$options = $entries->item(0)->childNodes;
		foreach ($options as $option)
		{
			/* @var $option DOMElement */
			if($option->hasAttribute("selected"))
			{
				return $option->getAttribute("value");
			}
		}
	}
	
	private function loadDOMByHTML($source)
	{
		libxml_use_internal_errors(true); // http://stackoverflow.com/questions/9149180/domdocumentloadhtml-error
		$doc = new DOMDocument();
		$doc->loadHTML($source);
		libxml_use_internal_errors(false);
		return $doc;
	}
	
	private function getDOMXPath($doc)
	{
		return new DOMXPath($doc);
	}	
}

?>