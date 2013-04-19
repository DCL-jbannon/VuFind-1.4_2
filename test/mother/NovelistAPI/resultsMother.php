<?php

class NovelistMother
{
	
	public function getResultContentByQueryEmptyFeaturedContent()
	{
		
		$jsonStr = '{
			"TitleInfo": {
			"isbns": [
			"9780767804301"
			]
		},
		"ClientIdentifier": "0767804309",
		"FeatureCount": 0,
		"FeatureContent": {
		}
		}';
		return json_decode($jsonStr);
	}
	
	public function getResultContentByQuery()
	{
		$jsonStr = '
		{
		  "TitleInfo": {
		    "ui": "064553",
		    "full_title": "Dead until dark",
		    "main_title": "Dead until dark",
		    "author": "Harris, Charlaine",
		    "full_author": "Harris, Charlaine",
		    "primary_isbn": "9780441008537",
		    "isbns": [
		      "9780441008537",
		      "9780441016990",
		      "9780441015979",
		      "9780441018253",
		      "9780441019335",
		      "9780441020300",
		      "9780613656504",
		      "9780606144247",
		      "9781587246302",
		      "9781841492995",
		      "9780575089365",
		      "9780754069966",
		      "0441008534",
		      "0441016995",
		      "0441015972",
		      "0441018254",
		      "0441019331",
		      "0441020305",
		      "0613656504",
		      "0606144242",
		      "1587246309",
		      "184149299X",
		      "0575089369",
		      "0754069966"
		    ],
		    "bookjacket_url": ""
		  },
		  "ClientIdentifier": "9780441008537",
		  "FeatureCount": 6,
		  "FeatureContent": {
		    "SeriesInfo": {
		      "category": "BookInfo",
		      "links": [],
		      "ui": "775564",
		      "full_title": "Sookie Stackhouse novels",
		      "main_title": null,
		      "alternate_title": "Southern vampire series",
		      "series_note": "The author recommends reading this series in publication order including novels and short stories, however each book is designed to stand alone.",
		      "series_titles": [
		        {
		          "volume": "1.",
		          "is_held_locally": null,
		          "ui": "064553",
		          "full_title": "Dead until dark",
		          "main_title": "Dead until dark",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441008537",
		          "primary_isbn": "9780441008537",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=064553&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441008537",
		            "9780441016990",
		            "9780441015979",
		            "9780441018253",
		            "9780441019335",
		            "9780441020300",
		            "9780613656504",
		            "9780606144247",
		            "9781587246302",
		            "9781841492995",
		            "9780575089365",
		            "9780754069966",
		            "0441008534",
		            "0441016995",
		            "0441015972",
		            "0441018254",
		            "0441019331",
		            "0441020305",
		            "0613656504",
		            "0606144242",
		            "1587246309",
		            "184149299X",
		            "0575089369",
		            "0754069966"
		          ]
		        },
		        {
		          "volume": "2.",
		          "is_held_locally": null,
		          "ui": "068973",
		          "full_title": "Living dead in Dallas",
		          "main_title": "Living dead in Dallas",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441009237",
		          "primary_isbn": "9780441009237",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=068973&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441009237",
		            "9780441018246",
		            "9780441016730",
		            "9780441018260",
		            "9780606121514",
		            "9780441019311",
		            "9781587249358",
		            "9781841493008",
		            "9780575089389",
		            "0441009239",
		            "0441018246",
		            "0441016731",
		            "0441018262",
		            "060612151X",
		            "0441019315",
		            "1587249359",
		            "1841493007",
		            "0575089385"
		          ]
		        },
		        {
		          "volume": "3.",
		          "is_held_locally": null,
		          "ui": "122402",
		          "full_title": "Club dead",
		          "main_title": "Club dead",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441010516",
		          "primary_isbn": "9780441010516",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=122402&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441010516",
		            "9780441019106",
		            "9780441019113",
		            "9780441018277",
		            "9780441019328",
		            "9780606121521",
		            "9781587249938",
		            "9781442097100",
		            "9780575089402",
		            "9781405634328",
		            "0441010512",
		            "0441019102",
		            "0441019110",
		            "0441018270",
		            "0441019323",
		            "0606121528",
		            "1587249936",
		            "1442097108",
		            "0575089407",
		            "1405634324"
		          ]
		        },
		        {
		          "volume": "4.",
		          "is_held_locally": null,
		          "ui": "124013",
		          "full_title": "Dead to the world",
		          "main_title": "Dead to the world",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441011674",
		          "primary_isbn": "9780441011674",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=124013&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441011674",
		            "9780441012183",
		            "9780441020447",
		            "9780441018284",
		            "9780441019342",
		            "9781596880160",
		            "9781439565636",
		            "9781841493701",
		            "9780606121538",
		            "9780575089426",
		            "9781448792689",
		            "0441011675",
		            "0441012183",
		            "0441020445",
		            "0441018289",
		            "044101934X",
		            "1596880163",
		            "1439565635",
		            "1841493708",
		            "0606121536",
		            "0575089423",
		            "1448792681"
		          ]
		        },
		        {
		          "volume": "5.",
		          "is_held_locally": null,
		          "ui": "133503",
		          "full_title": "Dead as a doornail",
		          "main_title": "Dead as a doornail",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441012794",
		          "primary_isbn": "9780441012794",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=133503&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441012794",
		            "9780441013333",
		            "9780441018307",
		            "9781937007607",
		            "9780441019359",
		            "9781597220040",
		            "9780606121545",
		            "9780786556786",
		            "9780575091054",
		            "9780575078871",
		            "0441012795",
		            "0441013333",
		            "0441018300",
		            "193700760X",
		            "0441019358",
		            "1597220043",
		            "0606121544",
		            "0786556781",
		            "0575091053",
		            "0575078871"
		          ]
		        },
		        {
		          "volume": "6.",
		          "is_held_locally": null,
		          "ui": "143874",
		          "full_title": "Definitely dead",
		          "main_title": "Definitely dead",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441014002",
		          "primary_isbn": "9780441014002",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=143874&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441014002",
		            "9780441014910",
		            "9780441018291",
		            "9780441019373",
		            "9781597222914",
		            "9780425262313",
		            "9781439559895",
		            "9780606121552",
		            "9780575091047",
		            "9780575078918",
		            "9780786570140",
		            "0441014003",
		            "0441014917",
		            "0441018297",
		            "0441019374",
		            "1597222917",
		            "0425262316",
		            "1439559899",
		            "0606121552",
		            "0575091045",
		            "057507891X",
		            "0786570148"
		          ]
		        },
		        {
		          "volume": "7.",
		          "is_held_locally": null,
		          "ui": "164944",
		          "full_title": "All together dead",
		          "main_title": "All together dead",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441014941",
		          "primary_isbn": "9780441014941",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=164944&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441014941",
		            "9780441015818",
		            "9780441018314",
		            "9780441019380",
		            "9781597225359",
		            "9780606121392",
		            "9780575083929",
		            "9781442097094",
		            "9780575083905",
		            "0441014941",
		            "0441015816",
		            "0441018319",
		            "0441019382",
		            "1597225355",
		            "0606121390",
		            "0575083921",
		            "1442097094",
		            "0575083905"
		          ]
		        },
		        {
		          "volume": "8.",
		          "is_held_locally": null,
		          "ui": "258069",
		          "full_title": "From dead to worse",
		          "main_title": "From dead to worse",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441015894",
		          "primary_isbn": "9780441015894",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=258069&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441015894",
		            "9780441017010",
		            "9780441018321",
		            "9780441019397",
		            "9781597227773",
		            "9780575083967",
		            "0441015891",
		            "0441017010",
		            "0441018327",
		            "0441019390",
		            "1597227773",
		            "0575083964"
		          ]
		        },
		        {
		          "volume": "9.",
		          "is_held_locally": null,
		          "ui": "305413",
		          "full_title": "Dead and gone",
		          "main_title": "Dead and gone",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441017157",
		          "primary_isbn": "9780441017157",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=305413&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441017157",
		            "9780441018512",
		            "9781597229876",
		            "9780441019212",
		            "9780441020942",
		            "9780575085503",
		            "9780575085527",
		            "0441017150",
		            "0441018513",
		            "1597229873",
		            "0441019218",
		            "0441020941",
		            "0575085509",
		            "0575085525"
		          ]
		        },
		        {
		          "volume": "10.",
		          "is_held_locally": null,
		          "ui": "344445",
		          "full_title": "Dead in the family",
		          "main_title": "Dead in the family",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441018642",
		          "primary_isbn": "9780441018642",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=344445&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441018642",
		            "9780441020157",
		            "9781410426505",
		            "9780441020683",
		            "9781937007119",
		            "9781448785254",
		            "0441018645",
		            "0441020151",
		            "1410426505",
		            "0441020682",
		            "1937007111",
		            "1448785251"
		          ]
		        },
		        {
		          "volume": "11.",
		          "is_held_locally": null,
		          "ui": "384687",
		          "full_title": "Dead reckoning: a Sookie Stackhouse novel",
		          "main_title": "Dead reckoning",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441020317",
		          "primary_isbn": "9780441020317",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=384687&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441020317",
		            "9781937007355",
		            "9781410435088",
		            "9780425256961",
		            "9780425261156",
		            "9781451756456",
		            "0441020313",
		            "1937007359",
		            "1410435083",
		            "0425256960",
		            "0425261158",
		            "1451756453"
		          ]
		        },
		        {
		          "volume": "12.",
		          "is_held_locally": null,
		          "ui": "10078941",
		          "full_title": "Deadlocked: a Sookie Stackhouse novel",
		          "main_title": "Deadlocked",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9781937007447",
		          "primary_isbn": "9781937007447",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=10078941&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9781937007447",
		            "9780425261378",
		            "9780425256381",
		            "9781410448224",
		            "9780425257050",
		            "1937007448",
		            "0425261379",
		            "0425256383",
		            "1410448223",
		            "0425257053"
		          ]
		        },
		        {
		          "volume": "13.",
		          "is_held_locally": null,
		          "ui": "10176736",
		          "full_title": "Dead ever after: a Sookie Stackhouse novel",
		          "main_title": "Dead ever after",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9781937007881",
		          "primary_isbn": "9781937007881",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=10176736&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9781937007881",
		            "9781410457073",
		            "9780425269206",
		            "193700788X",
		            "1410457079",
		            "0425269205"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "134397",
		          "full_title": "Night\'s edge",
		          "main_title": "Night\'s edge",
		          "author": "Shayne, Maggie",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780373770106",
		          "primary_isbn": "9780373770106",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=134397&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780373770106",
		            "9780373774289",
		            "0373770103",
		            "0373774281"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "159289",
		          "full_title": "Powers of detection: stories of mystery & fantasy",
		          "main_title": "Powers of detection",
		          "author": "Stabenow, Dana",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441011971",
		          "primary_isbn": "9780441011971",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=159289&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441011971",
		            "0441011977"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "131698",
		          "full_title": "Bite",
		          "main_title": "Bite",
		          "author": "Hamilton, Laurell K.",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780515139709",
		          "primary_isbn": "9780515139709",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=131698&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780515139709",
		            "9781417666898",
		            "051513970X",
		            "1417666897"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "150125",
		          "full_title": "My big fat supernatural wedding",
		          "main_title": "My big fat supernatural wedding",
		          "author": "Elrod, P. N. (Patricia Nead)",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9781597225281",
		          "primary_isbn": "9780312343606",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=150125&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780312343606",
		            "9781597225281",
		            "0312343604",
		            "1597225282"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "194602",
		          "full_title": "Many bloody returns",
		          "main_title": "Many bloody returns",
		          "author": "Harris, Charlaine",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441015221",
		          "primary_isbn": "9780441015221",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=194602&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441015221",
		            "0441015220"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "294840",
		          "full_title": "Unusual suspects: stories of mystery & fantasy",
		          "main_title": "Unusual suspects",
		          "author": "Stabenow, Dana",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441016372",
		          "primary_isbn": "9780441016372",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=294840&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441016372",
		            "0441016375"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "274096",
		          "full_title": "Wolfsbane and mistletoe",
		          "main_title": "Wolfsbane and mistletoe",
		          "author": "Harris, Charlaine",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441016334",
		          "primary_isbn": "9780441016334",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=274096&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441016334",
		            "0441016332"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "323155",
		          "full_title": "Must love hellhounds",
		          "main_title": "Must love hellhounds",
		          "author": "Harris, Charlaine",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780425229590",
		          "primary_isbn": "9780425229590",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=323155&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780425229590",
		            "0425229599"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "324178",
		          "full_title": "Strange brew",
		          "main_title": "Strange brew",
		          "author": "Elrod, P. N., (Patricia Nead)",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780312383367",
		          "primary_isbn": "9780312383367",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=324178&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780312383367",
		            "0312383363"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "326435",
		          "full_title": "A touch of dead: Sookie Stackhouse: the complete stories",
		          "main_title": "A touch of dead",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441017836",
		          "primary_isbn": "9780441017836",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=326435&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441017836",
		            "9781410423344",
		            "9780575094420",
		            "0441017835",
		            "1410423344",
		            "0575094427"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "346313",
		          "full_title": "Crimes by moonlight",
		          "main_title": "Crimes by moonlight",
		          "author": "Harris, Charlaine",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780425235638",
		          "primary_isbn": "9780425235638",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=346313&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780425235638",
		            "0425235637"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "360002",
		          "full_title": "Death\'s excellent vacation",
		          "main_title": "Death\'s excellent vacation",
		          "author": "Harris, Charlaine",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441018680",
		          "primary_isbn": "9780441018680",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=360002&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441018680",
		            "0441018688"
		          ]
		        },
		        {
		          "volume": "",
		          "is_held_locally": null,
		          "ui": "10011534",
		          "full_title": "The Sookie Stackhouse companion",
		          "main_title": "The Sookie Stackhouse companion",
		          "author": "Harris, Charlaine",
		          "full_author": "",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441019717",
		          "primary_isbn": "9780441019717",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=seriesinfo&srcIdentifier=064553&destIdentifier=10011534&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441019717",
		            "9780575097537",
		            "0441019714",
		            "0575097531"
		          ]
		        }
		      ]
		    },
		    "SimilarSeries": {
		      "category": "Suggestions",
		      "series": [
		        {
		          "ui": "774887",
		          "full_name": "Riley Jenson, Guardian novels",
		          "main_name": "Riley Jenson, Guardian novels",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780553804584",
		          "author": "Arthur, Keri",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=774887&version=2.1"
		            }
		          ],
		          "reason": "The Riley Jensen, Guardian series is somewhat more sexually explicit and slightly darker than Southern Vampire. Harris readers will appreciate Riley\'s inner strength, the multiple mysterious sub-plots, and her struggle to figure out where she belongs in a world in which the supernatural is real. -- Krista Biggs"
		        },
		        {
		          "ui": "767838",
		          "full_name": "Cassandra Palmer novels",
		          "main_name": "Cassandra Palmer novels",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780451460936",
		          "author": "Chance, Karen",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=767838&version=2.1"
		            }
		          ],
		          "reason": "Both series follow a human female with supernatural powers (Sookie is telepathic and Cassandra is clairvoyant) who get entangled in the vampire world. Both series have elements of the romance and mystery genres and are filled with a large cast of paranormal characters -- Becky Spratford"
		        },
		        {
		          "ui": "778765",
		          "full_name": "Alexandra Sabian series",
		          "main_name": "Alexandra Sabian series",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780553592672",
		          "author": "Holmes, Jeannie",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=778765&version=2.1"
		            }
		          ],
		          "reason": "If you like the \'Sookie Stackhouse novels\' you might also enjoy the \'Alexandra Sabian series.\'  Set in the American South, both are fast-paced and darkly romantic series involving vampires. -- Victoria Caplinger"
		        },
		        {
		          "ui": "759801",
		          "full_name": "Mercedes Thompson series",
		          "main_name": "Mercedes Thompson series",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441013814",
		          "author": "Briggs, Patricia",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=759801&version=2.1"
		            }
		          ],
		          "reason": "Blending romance, mystery, and adventure, The Mercedes Thompson Series and The Sookie Stackhouse novels both have strong-willed heroines who frequently interact with vampires and other magical creatures. -- Jessica Zellers"
		        },
		        {
		          "ui": "775176",
		          "full_name": "Esther Diamond novels",
		          "main_name": "Esther Diamond novels",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780373802333",
		          "author": "Resnick, Laura, 1962-",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=775176&version=2.1"
		            }
		          ],
		          "reason": "Fans of Charlaine Harris\' Sookie Stackhouse series will appreciate the Esther Diamond novels\' lively heroine (a struggling actress who waits tables in Manhattan) and the appealing combination of humor, mystery, and romance -- not to mention paranormal elements. -- Shauna Griffin"
		        },
		        {
		          "ui": "774889",
		          "full_name": "Fred the Mermaid novels",
		          "main_name": "Fred the Mermaid novels",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780515142228",
		          "author": "Davidson, MaryJanice",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=774889&version=2.1"
		            }
		          ],
		          "reason": "This series will appeal to Harris readers who enjoy the mix of fantasy and mystery with everyday life, as well as female protagonists who have more going on under the surface than meets the eye.  -- Krista Biggs"
		        },
		        {
		          "ui": "767188",
		          "full_name": "Night huntress",
		          "main_name": "Night huntress",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780061245084",
		          "author": "",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=767188&version=2.1"
		            }
		          ],
		          "reason": "Both series have vampires, mystery, romance and a tough heroine with a secret and a difficult choice -- to live in the human world or the vampire world. These are sexy and funny books filled with all sorts of supernatural creatures, but it is the wonderful heroines, Cat (of Night Huntress) and Sookie, who keep the readers clamoring for more. -- Becky Spratford"
		        },
		        {
		          "ui": "756346",
		          "full_name": "Greywalker",
		          "main_name": "Greywalker",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780451461070",
		          "author": "Richardson, Kat",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=756346&version=2.1"
		            }
		          ],
		          "reason": "Readers who enjoy playful and fun supernatural stories with spunky, endearing heroines and undercurrents of danger may enjoy both the \'Greywalker\' series and the \'Sookie Stackhouse novels.\'  -- Victoria Caplinger"
		        },
		        {
		          "ui": "766270",
		          "full_name": "Weather warden",
		          "main_name": "Weather warden",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780451459527",
		          "author": "Caine, Rachel",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarseries&srcIdentifier=064553&destIdentifier=766270&version=2.1"
		            }
		          ],
		          "reason": "This series will appeal to Harris fans on many levels, from the insertion of paranormal events and individuals into everyday life, the romance between a human (albeit one with special powers) and a supernatural being, to the strength of protagonist Joanne Baldwin. -- Krista Biggs"
		        }
		      ]
		    },
		    "SimilarAuthors": {
		      "category": "Suggestions",
		      "authors": [
		        {
		          "ui": "1003700",
		          "full_name": "Raye, Kimberly",
		          "main_name": "Raye, Kimberly",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1003700&version=2.1"
		            }
		          ],
		          "reason": "Both Raye and Harris have created rich worlds full of paranormals and alternate realities. Both have created histories of the races that populate their books, giving the reader a back-story on which everything else is based.  Both authors write strong female characters. -- Nanci Milone Hill"
		        },
		        {
		          "ui": "1015813",
		          "full_name": "Purser, Ann",
		          "main_name": "Purser, Ann",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1015813&version=2.1"
		            }
		          ],
		          "reason": "In their mystery series, Purser\'s Lois Mead and Harris\'s Lily Bard are both house cleaners who in their jobs just seem to get involved in solving murders.  These cozy mysteries are set in small towns and feature feisty female sleuth.  These books are fast paced and have a light hearted tone that makes them fun to read. -- Merle Jacob"
		        },
		        {
		          "ui": "1003186",
		          "full_name": "Moning, Karen Marie",
		          "main_name": "Moning, Karen Marie",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1003186&version=2.1"
		            }
		          ],
		          "reason": "Both Harris and Moning write about the lives of seemingly ordinary young women who find romance, adventure, and personal strength as they are swept up in worlds made unfamiliar by paranormal phenomena. Both do compelling world-building and character development. -- Katie-Rose Repp"
		        },
		        {
		          "ui": "1020092",
		          "full_name": "Colley, Barbara",
		          "main_name": "Colley, Barbara",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1020092&version=2.1"
		            }
		          ],
		          "reason": "Colley and Harris both write cozy mystery series that feature house cleaners in Southern cities. The stories feature inquisitive female sleuths who try to juggle their personal lives and their businesses. The women are feisty and just happen to find dead bodies wherever they go. The stories are fast paced yet light hearted tone -- Merle Jacob"
		        },
		        {
		          "ui": "1092969",
		          "full_name": "Harper, Molly",
		          "main_name": "Harper, Molly",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1092969&version=2.1"
		            }
		          ],
		          "reason": "Both Harris and Harper combine mysteries with engrossing romances, describing the adventures of charismatic and appealing protagonists as they navigate both ordinary and extraordinary challenges. Harper takes a comedic approach to the subject matter, where Harris is more serious and often darker. -- Katie-Rose Repp"
		        },
		        {
		          "ui": "1023196",
		          "full_name": "Garden, Nancy",
		          "main_name": "Garden, Nancy",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1023196&version=2.1"
		            }
		          ],
		          "reason": "These authors\' works are Suspenseful and Plot-driven, and they share: the genres \'Mystery stories\' and \'Fantasy fiction\' and the subject \'Vampires\'."
		        },
		        {
		          "ui": "1019506",
		          "full_name": "Mariotte, Jeff",
		          "main_name": "Mariotte, Jeff",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1019506&version=2.1"
		            }
		          ],
		          "reason": "These authors works are Suspenseful, Plot-driven, and Fast-paced, and they share: the genres Mystery stories and Fantasy fiction and the subjects Vampires, Murder, and Murder investigation."
		        },
		        {
		          "ui": "1038032",
		          "full_name": "Waggoner, Tim",
		          "main_name": "Waggoner, Tim",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1038032&version=2.1"
		            }
		          ],
		          "reason": "These authors works are Suspenseful, Engaging, and Fast-paced, and they share: the genres Mystery stories and Fantasy fiction and the subject Vampires."
		        },
		        {
		          "ui": "1055425",
		          "full_name": "Cast, P. C.",
		          "main_name": "Cast, P. C.",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1055425&version=2.1"
		            }
		          ],
		          "reason": "These authors works are Suspenseful, Engaging, and Plot-driven, and they share: the genre Fantasy fiction and the subject Vampires"
		        },
		        {
		          "ui": "1140711",
		          "full_name": "Brandman, Michael",
		          "main_name": "Brandman, Michael",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1140711&version=2.1"
		            }
		          ],
		          "reason": "These authors works are Suspenseful and Fast-paced, and they share: the genre Mystery stories and the subjects Murder, Small town life, and Murder investigation."
		        },
		        {
		          "ui": "1007543",
		          "full_name": "Briggs, Patricia",
		          "main_name": "Briggs, Patricia",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1007543&version=2.1"
		            }
		          ],
		          "reason": "These authors works are Suspenseful, Engaging, and Fast-paced, and they share: the genre Fantasy fiction and the subjects Vampires and Murder."
		        },
		        {
		          "ui": "1022819",
		          "full_name": "Shan, Darren",
		          "main_name": "Shan, Darren",
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similarauthors&srcIdentifier=064553&destIdentifier=1022819&version=2.1"
		            }
		          ],
		          "reason": "These authors works are Suspenseful, Plot-driven, and Fast-paced, and they share: the genre Fantasy fiction and the subject Vampires."
		        }
		      ]
		    },
		    "RelatedContent": {
		      "category": "RelatedContent",
		      "doc_types": [
		        {
		          "doc_type": "Author Read-alikes",
		          "content": [
		            {
		              "feature_author": "Zellers, Jessica",
		              "title": "Charlaine Harris",
		              "ui": "433518",
		              "links": [
		                {
		                  "label": "",
		                  "url": "http://novselect.ebscohost.com/Display/TreeNodeContent?format=json&profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&ui=433518&schema=http:&source=064553&version=2.1",
		                  "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=relatedcontent&srcIdentifier=064553&destIdentifier=433518&version=2.1"
		                },
		                {
		                  "label": "View In NoveList",
		                  "url": "http://search.ebscohost.com/login.aspx?direct=true&&db=neh&tg=UI&an=433518&site=novp-live",
		                  "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=relatedcontentplink&srcIdentifier=064553&destIdentifier=433518&version=2.1"
		                }
		              ]
		            },
		            {
		              "feature_author": "Mediatore Stover, Kaite",
		              "title": "Laurell K. Hamilton",
		              "ui": "502119",
		              "links": [
		                {
		                  "label": "",
		                  "url": "http://novselect.ebscohost.com/Display/TreeNodeContent?format=json&profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&ui=502119&schema=http:&source=064553&version=2.1",
		                  "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=relatedcontent&srcIdentifier=064553&destIdentifier=502119&version=2.1"
		                },
		                {
		                  "label": "View In NoveList",
		                  "url": "http://search.ebscohost.com/login.aspx?direct=true&&db=neh&tg=UI&an=502119&site=novp-live",
		                  "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=relatedcontentplink&srcIdentifier=064553&destIdentifier=502119&version=2.1"
		                }
		              ]
		            }
		          ]
		        },
		        {
		          "doc_type": "Award Winners",
		          "content": [
		            {
		              "feature_author": "",
		              "title": "Anthony Awards: Best Paperback Original",
		              "ui": "401413",
		              "links": [
		                {
		                  "label": "",
		                  "url": "http://novselect.ebscohost.com/Display/TreeNodeContent?format=json&profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&ui=401413&schema=http:&source=064553&version=2.1",
		                  "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=relatedcontent&srcIdentifier=064553&destIdentifier=401413&version=2.1"
		                },
		                {
		                  "label": "View In NoveList",
		                  "url": "http://search.ebscohost.com/login.aspx?direct=true&&db=neh&tg=UI&an=401413&site=novp-live",
		                  "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=relatedcontentplink&srcIdentifier=064553&destIdentifier=401413&version=2.1"
		                }
		              ]
		            }
		          ]
		        },
		        {
		          "doc_type": "Feature Articles",
		          "content": [
		            {
		              "feature_author": "Moyer, Jessica",
		              "title": "Twilight:  What to Read Next for Adult Readers",
		              "ui": "433147",
		              "links": [
		                {
		                  "label": "",
		                  "url": "http://novselect.ebscohost.com/Display/TreeNodeContent?format=json&profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&ui=433147&schema=http:&source=064553&version=2.1",
		                  "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=relatedcontent&srcIdentifier=064553&destIdentifier=433147&version=2.1"
		                },
		                {
		                  "label": "View In NoveList",
		                  "url": "http://search.ebscohost.com/login.aspx?direct=true&&db=neh&tg=UI&an=433147&site=novp-live",
		                  "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=relatedcontentplink&srcIdentifier=064553&destIdentifier=433147&version=2.1"
		                }
		              ]
		            }
		          ]
		        }
		      ]
		    },
		    "GoodReads": {
		      "category": "BookInfo",
		      "average_rating": 3.95,
		      "work_reviews_count": 310228,
		      "isbn13": 9780441008537,
		      "work_text_reviews_count": 13159,
		      "reviews_count": 290638,
		      "ratings_count": 232140,
		      "text_reviews_count": 11069,
		      "work_id": 301082,
		      "work_ratings_count": 245279,
		      "is_in_goodreads": true,
		      "links": [
		        {
		          "label": "",
		          "url": "http://www.goodreads.com/api/reviews_widget_iframe?did=Jroe494EySm5sAvcySLHLg&header_text=&isbn=9780441008537&links=100&review_back=fff&text=000",
		          "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=goodreads&srcIdentifier=064553&destIdentifier=9780441008537&version=2.1"
		        }
		      ]
		    },
		    "SimilarTitles": {
		      "category": "Suggestions",
		      "titles": [
		        {
		          "ui": "10105730",
		          "full_title": "Royal Street",
		          "main_title": "Royal Street",
		          "author": "Johnson, Suzanne",
		          "full_author": "Johnson, Suzanne, 1956-",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780765327796",
		          "primary_isbn": "9780765327796",
		          "reason": "Fans of fun supernatural thrillers with sassy heroines and well-developed Louisiana settings may enjoy both Royal Street (set in New Orleans) and Dead Until Dark. -- Victoria Caplinger",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10105730&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780765327796",
		            "0765327791"
		          ]
		        },
		        {
		          "ui": "10151365",
		          "full_title": "The black box",
		          "main_title": "The black box",
		          "author": "Connelly, Michael",
		          "full_author": "Connelly, Michael, 1956-",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780316069434",
		          "primary_isbn": "9780316069434",
		          "reason": "These books are Fast-paced, and they share: the genre Mystery stories and the subject Murder investigation.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10151365&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780316069434",
		            "9780316069427",
		            "9781600247248",
		            "9781619691681",
		            "9781455526956",
		            "9781478978084",
		            "9781619695580",
		            "9781619695597",
		            "9781619699878",
		            "9781619695603",
		            "9780446556729",
		            "0316069434",
		            "0316069426",
		            "1600247245",
		            "161969168X",
		            "1455526959",
		            "1478978082",
		            "1619695588",
		            "1619695596",
		            "1619699877",
		            "161969560X",
		            "0446556726"
		          ]
		        },
		        {
		          "ui": "130271",
		          "full_title": "Stealing the Elf-King\'s roses",
		          "main_title": "Stealing the Elf-King\'s roses",
		          "author": "Duane, Diane",
		          "full_author": "Duane, Diane",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780446609838",
		          "primary_isbn": "9780446609838",
		          "reason": "These books are Engaging and Plot-driven, and they share: the genres Mystery stories and Fantasy fiction and the subject Murder investigation.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=130271&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780446609838",
		            "0446609838"
		          ]
		        },
		        {
		          "ui": "10018024",
		          "full_title": "Explosive eighteen",
		          "main_title": "Explosive eighteen",
		          "author": "Evanovich, Janet",
		          "full_author": "Evanovich, Janet",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780345527714",
		          "primary_isbn": "9780345527714",
		          "reason": "These books are Upbeat, and they share: the genre Mystery stories and the subject Murder investigation.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10018024&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780345527714",
		            "9780739378229",
		            "9780345527738",
		            "9780307932501",
		            "9780345527721",
		            "9780307932525",
		            "9781616571344",
		            "0345527712",
		            "0739378228",
		            "0345527739",
		            "0307932508",
		            "0345527720",
		            "0307932524",
		            "1616571349"
		          ]
		        },
		        {
		          "ui": "10151435",
		          "full_title": "Notorious nineteen",
		          "main_title": "Notorious nineteen",
		          "author": "Evanovich, Janet",
		          "full_author": "Evanovich, Janet",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780345527745",
		          "primary_isbn": "9780345527745",
		          "reason": "These books are Upbeat, and they share: the genre Mystery stories and the subject Murder investigation.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10151435&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780345527745",
		            "9780739378236",
		            "9780307932549",
		            "9780307932563",
		            "9780345527752",
		            "9781467630092",
		            "0345527747",
		            "0739378236",
		            "0307932540",
		            "0307932567",
		            "0345527755",
		            "1467630098"
		          ]
		        },
		        {
		          "ui": "10078941",
		          "full_title": "Deadlocked: a Sookie Stackhouse novel",
		          "main_title": "Deadlocked",
		          "author": "Harris, Charlaine",
		          "full_author": "Harris, Charlaine",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9781937007447",
		          "primary_isbn": "9781937007447",
		          "reason": "These books are Upbeat, Engaging, and Plot-driven, and they share: the genres Mystery stories and Fantasy fiction and the subjects Louisiana, Murder, and Vampires.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10078941&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9781937007447",
		            "9780425261378",
		            "9780425256381",
		            "9781410448224",
		            "9780425257050",
		            "1937007448",
		            "0425261379",
		            "0425256383",
		            "1410448223",
		            "0425257053"
		          ]
		        },
		        {
		          "ui": "10120104",
		          "full_title": "Close your eyes",
		          "main_title": "Close your eyes",
		          "author": "Johansen, Iris",
		          "full_author": "Johansen, Iris",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780312611613",
		          "primary_isbn": "9780312611613",
		          "reason": "These books are Plot-driven and Fast-paced, and they share: the genre Mystery stories and the subject Murder investigation.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10120104&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780312611613",
		            "9781250010414",
		            "9781441884305",
		            "9781611734966",
		            "9781441884329",
		            "9781441884367",
		            "9781441884312",
		            "9781441884336",
		            "9781469213132",
		            "9781441884381",
		            "9781469296111",
		            "9781469296128",
		            "0312611617",
		            "1250010411",
		            "1441884300",
		            "1611734967",
		            "1441884327",
		            "144188436X",
		            "1441884319",
		            "1441884335",
		            "1469213133",
		            "1441884386",
		            "146929611X",
		            "1469296128"
		          ]
		        },
		        {
		          "ui": "10107547",
		          "full_title": "11th hour",
		          "main_title": "11th hour",
		          "author": "Patterson, James",
		          "full_author": "Patterson, James, 1947-",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780316097499",
		          "primary_isbn": "9780316097499",
		          "reason": "These books are Plot-driven and Fast-paced, and they share: the genres Mystery stories and Adult books for young adults and the subject Murder investigation.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10107547&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780316097499",
		            "9780316208086",
		            "9781607884651",
		            "9780446571821",
		            "9781611134148",
		            "9780446571838",
		            "9781619693173",
		            "9781619690493",
		            "9781619690479",
		            "9781619693449",
		            "9781619690486",
		            "0316097497",
		            "0316208086",
		            "1607884658",
		            "0446571822",
		            "1611134145",
		            "0446571830",
		            "1619693178",
		            "1619690497",
		            "1619690470",
		            "1619693445",
		            "1619690489"
		          ]
		        },
		        {
		          "ui": "10145288",
		          "full_title": "NYPD red",
		          "main_title": "NYPD red",
		          "author": "Patterson, James",
		          "full_author": "Patterson, James, 1947-",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780316199865",
		          "primary_isbn": "9780316199865",
		          "reason": "These books are Fast-paced, and they share: the genre Mystery stories and the subject Murder investigation.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10145288&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780316199865",
		            "9780316224130",
		            "9781455525195",
		            "9781611137101",
		            "9781619696143",
		            "9781619698086",
		            "9781619697645",
		            "9781455521548",
		            "9781619696136",
		            "0316199869",
		            "0316224138",
		            "1455525197",
		            "1611137101",
		            "1619696142",
		            "1619698080",
		            "1619697645",
		            "145552154X",
		            "1619696134"
		          ]
		        },
		        {
		          "ui": "10129940",
		          "full_title": "Bones are forever",
		          "main_title": "Bones are forever",
		          "author": "Reichs, Kathy",
		          "full_author": "Reichs, Kathy",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9781439102435",
		          "primary_isbn": "9781439102435",
		          "reason": "These books are Fast-paced, and they share: the genre Mystery stories and the subject Murder investigation.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=10129940&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9781439102435",
		            "9781442349001",
		            "9781410451187",
		            "9781476715711",
		            "9781439102442",
		            "9781594136528",
		            "1439102430",
		            "144234900X",
		            "1410451186",
		            "1476715718",
		            "1439102449",
		            "1594136521"
		          ]
		        },
		        {
		          "ui": "160929",
		          "full_title": "Moon called",
		          "main_title": "Moon called",
		          "author": "Briggs, Patricia",
		          "full_author": "Briggs, Patricia",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441013814",
		          "primary_isbn": "9780441013814",
		          "reason": "These books are Engaging and Fast-paced, and they share: the genre Fantasy fiction and the subjects Murder and Vampires.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=160929&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441013814",
		            "9780441019274",
		            "9781597227520",
		            "9781841496832",
		            "9780786561438",
		            "9781101022535",
		            "0441013813",
		            "0441019277",
		            "1597227528",
		            "1841496839",
		            "0786561432",
		            "1101022531"
		          ]
		        },
		        {
		          "ui": "235386",
		          "full_title": "Iron kissed",
		          "main_title": "Iron kissed",
		          "author": "Briggs, Patricia",
		          "full_author": "Briggs, Patricia",
		          "bookjacket_url": "http://contentcafe2.btol.com/ContentCafe/jacket.aspx?UserID=ebsco-test&Password=ebsco-test&Return=T&Type=S&Value=9780441015665",
		          "primary_isbn": "9780441015665",
		          "reason": "These books are Engaging and Fast-paced, and they share: the genre Fantasy fictio and the subjects  Vampires.",
		          "is_held_locally": null,
		          "links": [
		            {
		              "label": "",
		              "url": "",
		              "log_url": "http://novselect.ebscohost.com/Logging/LogFeatureClick?profile=s9038887.main.novsel2&password=dGJyMOPmtUqxrLFN&featureType=similartitles&srcIdentifier=064553&destIdentifier=235386&version=2.1"
		            }
		          ],
		          "isbns": [
		            "9780441015665",
		            "9781937007140",
		            "9781597228671",
		            "9781841496856",
		            "9781101022559",
		            "0441015662",
		            "1937007146",
		            "1597228672",
		            "1841496855",
		            "1101022558"
		          ]
		        }
		      ]
		    }
		  }
		}';
		
		return json_decode($jsonStr);
	}
	
}
?>