<?php
require_once dirname(__FILE__).'/OverDriveAPIException.php';

interface IOverDriveHttpResponse{};

class OverDriveHttpResponse implements IOverDriveHttpResponse{
	
	/**
	 * Receive a 200 or 401 code is ok. Other than that is an error.
	 * @param integer $reponseCode
	 * @return Boolean | Throw Exception
	 */
	public function checkResponseCode($reponseCode)
	{
		if ($reponseCode === 200)
		{
			return true;
		}
		
		if ($reponseCode === 401)
		{
			return false;
		}
		
		throw new OverDriveHttpResponseException($reponseCode);
	}
	
}

?>