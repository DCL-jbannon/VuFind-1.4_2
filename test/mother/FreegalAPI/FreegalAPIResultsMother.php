<?php

class FreegalAPIResultsMother
{
	
	public function getSongsSameAlbum()
	{
		$xmlString  = '<?xml version="1.0" encoding="UTF-8" ?>';
		$xmlString .= "<Songs>";
		$xmlString .= $this->getSongXml();
		$xmlString .= $this->getSongXml();
		$xmlString .= "</Songs>";
		return simplexml_load_string($xmlString);
	}
	
	public function getEmptyResults()
	{
		$xmlString = "<Songs><message>No Records</message></Songs>";
		return simplexml_load_string($xmlString);
	}
	
	private function getSongXml()
	{
		$xmlString  = "<Song><ProdID>2538590</ProdID><ProductID>221776</ProductID><ReferenceID>221776</ReferenceID>";
		$xmlString .= "  <Title><![CDATA[Sempre Sardanes Àlbum 6]]></Title>";
		$xmlString .= "  <SongTitle><![CDATA[Girona M'enamora]]></SongTitle>";
		$xmlString .= "  <ArtistText><![CDATA[Cobla Montgrins]]></ArtistText>";
		$xmlString .= "  <provider_type>ioda</provider_type>";
		$xmlString .= "  <Artist>Cobla Montgrins</Artist>";
		$xmlString .= "  <Advisory>F</Advisory>";
		$xmlString .= "  <Composer/>";
		$xmlString .= "  <Genre>Folklore</Genre>";
		$xmlString .= "  <freegal_url><![CDATA[https://freegalmusic.com/services/login/c1b16052497962551ea7482fc86acc1ec3b39ace/11/23025006182976/221776/Q29ibGEgTW9udGdyaW5z/aW9kYQ==]]></freegal_url>";
		$xmlString .= "  <Album_Artwork><![CDATA[http://music.libraryideas.com/ioda/221776/221776.jpg?nvb=20121113214750&nva=20121113224750&token=54dbdc0366429ed977e27]]></Album_Artwork>";
		$xmlString .= "</Song>";
		return $xmlString;
	}
	
}


?>