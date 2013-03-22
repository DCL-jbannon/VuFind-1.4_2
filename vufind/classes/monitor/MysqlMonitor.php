<?php
require_once dirname(__FILE__).'/BaseMonitor.php';

class MysqlMonitor extends BaseMonitor
{
	private $link;
	private $sql;

	public function __construct($host, $database, $user, $password, $sql)
	{
		parent::__construct();
		
		$this->link = mysql_connect($host, $user, $password);
		if (!$this->link) {
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($database, $this->link);
		$this->sql = $sql;
		
	}
	
	public function exec($sleep = false /**Tests Purpouses **/)
	{
		parent::beforeExec($sleep);
		$result = mysql_query($this->sql, $this->link);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $this->sql;
			die($message);
		}
		$result = mysql_fetch_assoc($result);
		parent::afterExec();
		
		return ($result['id'] == "1");
	}

}

?>