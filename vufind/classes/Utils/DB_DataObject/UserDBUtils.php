<?php
require_once dirname(__FILE__).'/BaseDB_DataObjectUtils.php';
require_once dirname(__FILE__).'/../../../web/services/MyResearch/lib/User.php';

class UserDBUtils extends BaseDB_DataObjectUtils
{
	
	public function getClasDBName()
	{
		return "User";
	}
	
}

?>