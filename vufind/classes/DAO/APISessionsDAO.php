<?php
require_once dirname(__FILE__).'/../../web/sys/serverAPI/APISessions.php';
require_once dirname(__FILE__).'/BaseDAO.php';

interface IAPISessionsDAO{}

class APISessionsDAO extends BaseDAO implements IAPISessionsDAO
{
	
	public function getEntityName()
	{
		return "APISessions";
	}
	
	public function insertSession($clientId, $accessToken)
	{
		$apis = $this->getEmptyEntity();
		$apis->setClientId($clientId);
		$apis->setAccessToken($accessToken);
		$this->insert($apis);
	}
	
	public function getSessionByAccessToken($accessToken)
	{
		$apis = $this->getEmptyEntity();
		$apis->setAccessToken($accessToken);
		$apis->orderBy('id DESC');
		$apis->limit(1);
		if($apis->find(true))
		{
			$apis->fetch();
			return $apis;
		}
		return self::noResult();
	}
	
	private function getEmptyEntity()
	{
		return new APISessions();
	}
	
}
?>