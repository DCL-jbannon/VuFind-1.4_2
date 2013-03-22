<?php

class OverDriveResultsMother
{
	
	const accessToken = "AAEAACWEA9cbeEMQWKvYvNA5xO-fEXp83AE2i-lBZd9ubERzYOcSG3w5bd6Q3WEbijyNAIbav4fn7MLWosyXZktPImr";
	const productsUrl = "https://api.overdrive.com/v1/collections/L1BGAEAAA2f/products";
	
	const totalCopies = "aDummyTotalCopiesValue";
	const onHold = "aDummyOnHoldValue";
	const availableCopies = "aDummyAvailableCopiesValue";

	public function getValidLoginResult($accessToken = NULL)
	{
		if(!$accessToken) $accessToken = self::accessToken;
		
		$object = new stdClass();
		$object->access_token = $accessToken;
		$object->token_type = "bearer";
		$object->expires_in = "3600";
		$object->scope = "LIB META AVAIL SRCH";
		return $object;
	}
	
	public function getInfoLibraryResult($productUrl = NULL)
	{
		if(!$productUrl) $productUrl = self::productsUrl;
		
		$object = new stdClass();
		$object->id = "1344";
		$object->name = "Douglas County Libraries (CO)";
		$object->type = "Library";
		
		$object->links->self->href = "https://api.overdrive.com/v1/libraries/1344";
		$object->links->self->type = "application/vnd.overdrive.api+json";
		
		$object->links->products->href = $productUrl;
		$object->links->products->type = "application/vnd.overdrive.api+json";
		
		$object->links->advantageAccounts->href = "https://api.overdrive.com/v1/libraries/1344/advantageAccounts";
		$object->links->advantageAccounts->type = "application/vnd.overdrive.api+json";
		
		$object->formats = array();
		$object->formats[0]->id="audiobook-wma";
		$object->formats[0]->name="OverDrive WMA Audiobook";
		$object->formats[1]->id="ebook-epub-adobe";
		$object->formats[1]->name="Adobe EPUB eBook";
		
		return $object;
	}
	
	
	public function getDigitalCollectionResult()
	{
		$object = new stdClass();
		$object->limit = "25";
		$object->offset = "0";
		$object->totalItems = "14669";
		$object->id = "L1BGAEAAA2f";
		$object->products = array();
		$object->products[0]->id = "82cdd641-857a-45ca-8775-34eede35b238";
		$object->products[0]->mediaType = "eBook";
		$object->products[0]->title = "Fifty Shades of Grey";
		$object->products[0]->subtitle = "Fifty Shades Trilogy, Book 1";
		$object->products[0]->series = "Fifty Shades Trilogy";
		$object->products[0]->primaryCreator->role = "Author";
		$object->products[0]->primaryCreator->name = "E L James";
		$object->products[0]->formats=array();
		$object->products[0]->formats[0]->id="ebook-epub-adobe";
		$object->products[0]->formats[0]->name="Adobe EPUB eBook";
		$object->products[0]->formats[1]->id="ebook-kindle";
		$object->products[0]->formats[1]->name="Kindle Book";
		$object->products[0]->images->thumbnail = "http://images.contentreserve.com/ImageType-200/0111-1/{82CDD641-857A-45CA-8775-34EEDE35B238}Img200.jpg";
		$object->products[0]->images->type = "image/jpeg";
		$object->products[0]->contentDetails=array();
		$object->products[0]->contentDetails[0]->href="www.emedia2go.org/ContentDetails.htm?ID=82cdd641-857a-45ca-8775-34eede35b238";
		$object->products[0]->contentDetails[0]->type="text/html";
		$object->products[0]->contentDetails[0]->account->id=1344;
		$object->products[0]->contentDetails[0]->account->name="Douglas County Libraries (CO)";
		$object->products[0]->links->self->href = "https://api.overdrive.com/v1/collections/L1BGAEAAA2f/products/82cdd641-857a-45ca-8775-34eede35b238";
		$object->products[0]->links->self->type = "application/vnd.overdrive.api+json";
		$object->products[0]->links->metadata->href = "https://api.overdrive.com/v1/collections/L1BGAEAAA2f/products/82cdd641-857a-45ca-8775-34eede35b238/metadata";
		$object->products[0]->links->metadata->type = "application/vnd.overdrive.api+json";
		$object->products[0]->links->availability->href = "https://api.overdrive.com/v1/collections/L1BGAEAAA2f/products/82cdd641-857a-45ca-8775-34eede35b238/availability";
		$object->products[0]->links->availability->type = "application/vnd.overdrive.api+json";
		return $object;
	}
	
	public function getItemAvailabilityResult()
	{
		$object = new stdClass();
		$object->id = "30AF0828-3A80-4701-938F-D867930A0D88";
		$object->links->self->href = "https://api.overdrive.com/v1/collections/L1BGAEAAA2f/products/30AF0828-3A80-4701-938F-D867930A0D88/availability";
		$object->links->self->type = "application/vnd.overdrive.api+json";
		$object->available = true;
		$object->copiesOwned = "1";
		$object->copiesAvailable = "1";
		$object->numberOfHolds = "0";
		
	}
	
	public function getItemMetadataResult()
	{
		$object = new stdClass();
		$object->id = "30af0828-3a80-4701-938f-d867930a0d88";
		$object->mediaType = "Audiobook";
		$object->title = "Kabul Beauty School";
		$object->subtitle = "An American Woman Goes Behind the Veil";
		$object->sortTitle = "Kabul Beauty School";
		$object->edition = "Unabridged";
		$object->publisher = "Blackstone Audio, Inc.";
		$object->publishDate = "05/01/2007";
		$object->creators[0]->role = "Author";
		$object->creators[0]->name = "Deborah Rodriguez";
		$object->creators[0]->fileAs = "Rodriguez, Deborah";
		$object->creators[0]->bioText = "<P><B>Deborah Rodriguez</B>, born and raised in Holland, Michigan, has worked as a hairdresser since 1979. In 2002, she helped found the Kabul Beauty School";
		$object->creators[1]->role = "Author";
		$object->creators[1]->name = "Kristin Ohlson";
		$object->creators[1]->fileAs = "Ohlson, Kristin";
		$object->creators[1]->bioText = "<P><B>Deborah Rodriguez</B>, born and raised in Holland, Michigan, has worked as a hairdresser since 1979.";
		$object->creators[2]->role = "Narrator";
		$object->creators[2]->name = "Bernadette Dunne";
		$object->creators[2]->fileAs = "Dunne, Bernadette";
		$object->creators[2]->bioText = "<P><B>Bernadette Dunne</B> is the winner of six AudioFile Earphones Awards and has twice been nominated for the prestigious Audi.";
		$object->links->self->href = "https://api.overdrive.com/v1/collections/L1BGAEAAA2f/products/30af0828-3a80-4701-938f-d867930a0d88/metadata";
		$object->links->self->type = "application/vnd.overdrive.api+json";
		$object->images->thumbnail->href = "http://images.contentreserve.com/ImageType-200/0887-1/{30AF0828-3A80-4701-938F-D867930A0D88}Img200.jpg";
		$object->images->thumbnail->type = "image/jpeg";
		$object->images->cover->href = "http://images.contentreserve.com/ImageType-100/0887-1/{30AF0828-3A80-4701-938F-D867930A0D88}Img100.jpg";
		$object->images->cover->type = "image/jpeg";
		$object->languages[0]->code = "en";
		$object->languages[0]->name = "English";
		$object->isPublicDomain = false;
		$object->isPublicPerformanceAllowed = false;
		$object->gradeLevels[0] = "Grade 9";
		$object->gradeLevels[1] = "Grade 10";
		$object->gradeLevels[2] = "Grade 11";
		$object->gradeLevels[3] = "Grade 12";
		$object->shortDescription = "Beneath the Veil of Afghan Women";
		$object->fullDescription = "Beneath the Veil of Afghan Women.Most Westerners now working in Afghanistan spend their time tucked inside the wall of a military compound or embassy.";
		$object->starRating = 2.3; //Float
		$object->popularity = 25; //Integer
		$object->subjects[0]->value = "Biography & Autobiography";
		$object->subjects[1]->value = "Nonfiction";
		$object->reviews[0]->source = "<P><i>Publishers Weekly</i> (starred review)";
		$object->reviews[0]->content = "<P>A terrific opening chapter—colorful, suspenseful, funny—ushers readers into the curious closed world of Afghan women...";
		$object->reviews[0]->premium = false;
		$object->reviews[1]->source = "<a href=\"http://www.audiofilemagazine.com\" target=\"_blank\"><img src=\"http://images.contentreserve.com/audiofile_logo.jpg\" alt=\"AudioFile Magazine\" border=\"0\" /></a>";
		$object->reviews[1]->content = "Debbie Rodriguez is a new kind of secular missionary. She went to Afghanistan after the fall of the Taliban, planning to work as a nurse's aide. But when her skills as a hairdresser were discovered, she found herself training young Afghan women in the trade. By learning a marketable skill, the women gained security, autonomy, and dignity. It's difficult for Westerners to understand the culture in which women live in Kabul, and Rodriguez provides plenty of shocking details. Listeners are aided in their sojourn to this land by the clear and confident narration of Bernadette Dunne. Her delivery will make listeners feel it's the author herself telling them about her students, love life, and  political problems. Rodriquez's experiences are fascinating, and Dunne makes them more so.  D.L.G. (c) AudioFile 2008, Portland, Maine";
		$object->reviews[1]->premium = true;
		$object->formats[0]->id = "audiobook-wma";
		$object->formats[0]->name = "OverDrive WMA Audiobook";
		$object->formats[0]->fileName = "KabulBeautySchool";
		$object->formats[0]->identifiers[0]->type = "ISBN";
		$object->formats[0]->identifiers[0]->value = "9781433239458";
		$object->formats[0]->fileSize = 327401;
		$object->formats[0]->onSaleDate = "04/12/2007";
		$object->formats[0]->rights[0]->type = "PlayOnPC";
		$object->formats[0]->rights[0]->value = 1;
		$object->formats[0]->rights[1]->type = "PlayOnPCCount";
		$object->formats[0]->rights[1]->value = -1;
		$object->formats[0]->rights[2]->type = "BurnToCD";
		$object->formats[0]->rights[2]->value = 1;
		//There are more rights, but we do not use them....so....
		$object->formats[0]->samples[0]->source = "Introduction/Chapter 1";
		$object->formats[0]->samples[0]->url = "http://excerpts.contentreserve.com/FormatType-25/0887-1/129776-KabulBeautySchool.wma";
		$object->formats[1]->id = "ebook-pdf-adobe";
		$object->formats[1]->name = "Ebook";
		$object->formats[1]->fileSize = 0;
		
		return $object;
	}
	
	
	public function getErrorResponse()
	{
		$object = new stdClass();
		$object->message = "An unexpected error has occurred.";
		$object->token = "b31e17ef-608f-442f-92f1-d0c883673f5a";
	}
	
	public function getItemCheckedOutStatus($canBe)
	{
		return $this->getItemDetails($canBe);
	}
	
	public function getItemPlaceHoldStatus($canBe)
	{
		return $this->getItemDetails(NULL, $canBe);
	}
	
	public function getItemWishListStatus($canBe)
	{
		return $this->getItemDetails(NULL, NULL, $canBe);
	}
	
	public function getItemDetails($canCheckOut = NULL, $canHold = NULL, $canAddWishList = NULL)
	{
		$details = new stdClass();
		$details->AvailableCopies = self::availableCopies;
		$details->TotalCopies = self::totalCopies;
		$details->OnHoldCount = self::onHold;
		$details->CanCheckout = $canCheckOut;
		$details->CanHold = $canHold;
		$details->CanAddWishList = $canAddWishList;
		return $details;
	}
	
	public function getItemDetailsHoldOption()
	{
		$details = $this->getItemDetails();
		$details->formatIdHold = 25;
		return $details;
	}
	
	public function getPatronCirculation()
	{
		$patronCiculation->Checkouts[] = array("ItemId" =>  "0E325B48-E1A7-465A-8914-3EA6E46227B6",
											   "Title"=>    "Australia's North West",
											   "Expires" => "Mar 15 2013  1:15PM",
											   "Link" => "aDummyLink-0E325B48",
											   "ChooseFormat"=>false);
		
		$patronCiculation->Checkouts[] = array("ItemId" =>  "EA87339B-9B92-423E-B413-D9A17BF33AF9",
											   "Title"=>    "The Cat Who Said Cheese",
											   "Expires" => "Mar 29 2013  1:12PM",
											   "Link" => "aDummyLink-EA87339B",
											   "ChooseFormat"=>false);
		
		$patronCiculation->Checkouts[] = array("ItemId" =>  "833283EE-3A23-45FB-B5DF-217DEC6C2D02",
											   "Title"=>    "The Cat Who Said Cheese",
											   "Expires" => "Mar 25 2019  2:12AM",
											   "Link" => "",
											   "ChooseFormat"=>true);
		
		$patronCiculation->Holds[] = array("ItemId" => "8489B13C-FAFD-4751-81EE-1F0F090EFFEE",
				                           "FormatId" => 425,
										   "Title" =>  "The Poisonwood Bible",
				 						   "UserPosition" =>  5,
				 						   "QueueLength" => 98);
		
		$patronCiculation->Holds[] = array("ItemId" => "82CDD641-857A-45CA-8775-34EEDE35B238",
										   "FormatId" => 50,
										   "Title" =>  "Fifty Shades of Grey",
				   						   "UserPosition" =>  19,
				   						   "QueueLength" =>  91);
		
		$patronCiculation->WishList[] = array("ItemId" => "7C0C0960-D041-468D-ABCC-3DD4F43ACB2B",
											  "Title"  => "If You Were Mine");
		
		$patronCiculation->WishList[] = array("ItemId" => "F1430E53-C02F-4FB6-85FE-6319BDDF7084",
				                              "Title"  => "I Only Have Eyes For You ");
		
		return $patronCiculation;
	}

}
?>