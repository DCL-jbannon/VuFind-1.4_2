<?php
require_once dirname(__FILE__).'/../../../vufind/classes/API/Server/ServerAPI.php';

/***
 * Integration Tests
 */
class ServerAPITests extends PHPUnit_Framework_TestCase
{
	
	private $ch;
	private static $accessToken;
	private $headers;
	private $clientKey = "DCIntegrationTestsDEV";
	private $clientAuthCode = "56623829645662382964";

	public function setUp()
	{
		$this->ch = curl_init("http://dcl.localhost/APIV2/Home");
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->ch, CURLOPT_USERAGENT, "DCL Integration Tests");
	}

	/**
	* method callAPI 
	* when noMethodHeaderDefine
	* should returnErrorMessage
	*/
	public function test_001_callAPI_noMethodHeaderDefine_returnErrorMessage()
	{
		$expected = MethodHeaderNotFoundException::code;
		$actual = $this->exec();
		$this->assertEquals($expected, $actual->errorCode);
	}
	
	/**
	 * method callAPI
	 * when methodDoesNotExists
	 * should returnErrorMessage
	 */
	public function test_002_callAPI_methodDoesNotExists_returnErrorMessage()
	{
		$expected = APIMethodNotExistsException::code;
		$this->setMethodHeader("aNonExistingMethod");
		$actual = $this->exec();
		$this->assertEquals($expected, $actual->errorCode);
	}
	
	/**
	* method callAPI 
	* when authHeader
	* should returnAccessToken
	*/
	public function test_003_callAPI_authHeader_returnAccessToken()
	{
		$this->setMethodHeader("auth");
		$this->setAuthorizationHeader();
		
		$actual = $this->exec();
		$this->assertNotEmpty($actual->accessToken);
		self::$accessToken = $actual->accessToken;
	}
	
	/**
	 * method callAPI
	 * when noAccessToken
	 * should returnErrorMessage
	 */
	public function test_004_callAPI_noAccessToken_returnErrorMessage()
	{
		$expected = AccessTokenHeaderNotFoundException::code;
		$this->setMethodHeader("dummy");
		
		$actual = $this->exec();
		$this->assertEquals($expected, $actual->errorCode);
	}
	
	/**
	 * method callAPI
	 * when NonValidAccessToken
	 * should returnErrorMessage
	 */
	public function test_005_callAPI_NonValidAccessToken_returnErrorMessage()
	{
		$expected = AccessTokenNotValidException::code;
		$this->setMethodHeader("dummy");
		$this->setAccessTokenHeader("aNonValidAccessToken");
		
		$actual = $this->exec();
		$this->assertEquals($expected, $actual->errorCode);
	}
	
	/**
	 * method callAPI
	 * when validAccessToken
	 * should executesCorrectly
	 */
	public function test_006_callAPI_validAccessToken_executesCorrectly()
	{
		$expected = "Wolrd Test Integration";
		$this->setMethodHeader("dummy");
		$this->setAccessTokenHeader(self::$accessToken);
		$this->setJSONContent(array("whoamI"=>"Test Integration"));
		
		$actual = $this->exec();
		$this->assertEquals($expected, $actual->Hello);
	}
	
	/**
	* method authRL 
	* when called
	* should executesCorrectly
	*/
	public function test_007_authRL_called_executesCorrectly()
	{
		$this->setMethodHeader("authRL");
		$this->setAccessTokenHeader(self::$accessToken);
		$this->setJSONContent(array("username"=>"23025006182976", "password"=>"9745"));
		
		$actual = $this->exec();
		$this->assertEquals("JUAN BAUTISTA (S) GIMENEZ SENDIU", $actual->fullname);
	}
	
	/**
	 * method getItemDetails
	 * when notFound
	 * should executesCorrectly
	 */
	public function test_008_getItemDetails_printTitle_executesCorrectly()
	{
		$this->setMethodHeader("getItemDetails");
		$this->setAccessTokenHeader(self::$accessToken);
		$this->setJSONContent(array("id"=>"0"));
	
		$actual = $this->exec();
		$this->assertEquals(array(), $actual);
	}
	
	/**
	 * method getItemDetails
	 * when printTitle
	 * should executesCorrectly
	 */
	public function test_009_getItemDetails_printTitle_executesCorrectly()
	{
		$this->setMethodHeader("getItemDetails");
		$this->setAccessTokenHeader(self::$accessToken);
		$this->setJSONContent(array("id"=>"1088524"));
	
		$actual = $this->exec();
		$this->assertEquals("1088524", $actual->uniqueID);
	}
	
	/**
	 * method getItemDetails
	 * when eContent
	 * should executesCorrectly
	 */
	public function test_010_getItemDetails_eContent_executesCorrectly()
	{
		$this->setMethodHeader("getItemDetails");
		$this->setAccessTokenHeader(self::$accessToken);
		$this->setJSONContent(array("id"=>EContentRecord::prefixUnique."51"));
	
		$actual = $this->exec();
		$this->assertEquals(EContentRecord::prefixUnique."51", $actual->uniqueID);
	}
	
	/**
	 * method searchKeyword
	 * when search3M
	 * should executesCorrectly
	 */
	public function test_011_searchKeyword_search3M_executesCorrectly()
	{
		$this->setMethodHeader("searchKeyword");
		$this->setAccessTokenHeader(self::$accessToken);
		$this->setJSONContent(array("searchTerm"=>"Downloadables 3M", "page"=>1, "formatCategory" => ""));
	
		$actual = $this->exec();
		$this->assertEquals("3M Cloud Library", $actual[0]->secondAuthor);
	}
	
	/**
	 * method searchKeyword
	 * when searchDog  Return just print titles
	 * should executesCorrectly
	 */
	public function test_012_searchKeyword_searchDog_executesCorrectly()
	{
		$this->setMethodHeader("searchKeyword");
		$this->setAccessTokenHeader(self::$accessToken);
		$this->setJSONContent(array("searchTerm"=>"Dog", "page"=>1, "formatCategory" => ""));
	
		$actual = $this->exec();
		//var_dump($actual);
		$this->assertEquals("New York :", $actual[0]->publicationPlace);
	}
	
	/**
	 * method searchKeywordRL
	 * when called
	 * should executesCorrectly
	 */
	public function test_013_searchKeywordRL_called_executesCorrectly()
	{
		$this->setMethodHeader("searchKeywordRL");
		$this->setAccessTokenHeader(self::$accessToken);
		$this->setJSONContent(array("searchTerm"=>"Dog", "start"=>1, "formatCategory" => "")); //NumRecords always is 20!
	
		$actual = $this->exec();
		$this->assertEquals("New York :", $actual[0]->publicationPlace);
	}
	
	//privates
	private function setJSONContent($postContent)
	{
		$jsonPost = json_encode($postContent);
		
		$this->headers[] = "Content-Type: application/json";
		$this->headers[] = "Content-Length: ".strlen($jsonPost);
		
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $jsonPost);
	}
	
	private function setAccessTokenHeader($accessToken)
	{
		$this->headers[] = ServerAPIConstants::AccessTokenHeaderKey.": ".$accessToken;
	}
	
	private function setAuthorizationHeader()
	{
		global $configArray;
		$authString = ServerAPIUtils::generateAuthString($this->clientAuthCode, $configArray['ServerAPI']['secretKey']);
		$this->headers[] = ServerAPIConstants::AuthHeaderKey.": ".$this->clientKey.";".$authString;
	}
	
	private function setMethodHeader($methodName)
	{
		$this->headers[] = ServerAPIConstants::MethodHeaderKey.": ".$methodName;
	}
	
	private function exec()
	{
		if(!empty($this->headers))
		{
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
		}
		
		//curl_setopt($this->ch, CURLOPT_PROXY, "127.0.0.1:8888");
		$result = curl_exec($this->ch);
		var_dump($result);
		return json_decode($result);
	}
	
	public function tearDown()
	{
		curl_close($this->ch);
		parent::tearDown();
	}
	
}
?>