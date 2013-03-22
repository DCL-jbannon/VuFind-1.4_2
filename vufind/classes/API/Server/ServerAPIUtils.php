<?php
require_once dirname(__FILE__).'/ServerAPIException.php';
require_once dirname(__FILE__).'/ServerAPIConstants.php';

interface IServerAPIUtils{}

class ServerAPIUtils implements IServerAPIUtils
{
	public function generateAccessToken($secret)
	{
		$hashedString = $this->hashString($secret, uniqid().mktime().mt_rand());
		return base64_encode($hashedString);
	}

	public function isValidAuthString($authString, $clientAuthCode, $secret)
	{
		$hashedString = self::generateAuthString($clientAuthCode, $secret);
		return $authString===$hashedString;
	}
	
	public static function generateAuthString($clientAuthCode, $secret)
	{
		return base64_encode(self::hashString($secret, $clientAuthCode));
	}

	public function getAuthHeader($headers)
	{
		return $this->getHeadersValue($headers, ServerAPIConstants::AuthHeaderKey, "AuthorizationHeaderNotFoundException");
	}
	
	public function getAccessTokenHeader($headers)
	{
		return $this->getHeadersValue($headers, ServerAPIConstants::AccessTokenHeaderKey, "AccessTokenHeaderNotFoundException");
	}
	
	public function getMethodHeader($headers)
	{
		return $this->getHeadersValue($headers, ServerAPIConstants::MethodHeaderKey, "MethodHeaderNotFoundException");
	}
	
	//PRIVATES
	private function getHeadersValue($headers, $headerType, $exceptionClass)
	{
		if(!isset($headers[$headerType]))
		{
			throw new $exceptionClass();
		}
		return $headers[$headerType];
	}
	
	private function hashString($secret, $stringToHash)
	{
		return hash_hmac("sha256", $stringToHash, $secret, TRUE);
	}
}
?>