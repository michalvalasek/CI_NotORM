<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class CI_NotORM
{
	private $connection;	
	private $lib_location = "NotORM/NotORM.php";
	
	public function __construct()
	{
		if ( ! file_exists(dirname(__FILE__).'/'.$this->lib_location) ) {
			show_error('NotORM library not present at '.dirname(__FILE__).'/'.$this->lib_location);
		}
		include $this->lib_location;
		
		$CI = & get_instance();
		$CI->notorm = $this->connect();
		
		log_message('debug', "Selector Class Initialized");
	}
	
	private function connect()
	{
		include(APPPATH.'config/database'.EXT);
		if ( ! isset($db) OR count($db) == 0)
		{
			show_error('No database connection settings were found in the database config file.');
		}
		
		if ( ! isset($active_group) OR ! isset($db[$active_group]))
		{
			show_error('You have specified an invalid database connection group.');
		}
		$params = $db[$active_group];
		if ( !isset($params['dbdriver'])
			 || !isset($params['database'])
			 || !isset($params['hostname'])
			 || !isset($params['username'])
			 || !isset($params['password']) ) 
		{
			show_error('Invalid DB connection settings.');
		}
		
		try {	
			$this->connection = new PDO("$params[dbdriver]:dbname=$params[database];host=$params[hostname]",$params['username'],$params['password']);
			return new NotORM($this->connection);
		}
		catch (Exception $e) {
			show_error('Error during DB connection: '.$e->message);
		}
	}
	
}

/* End of file Selector.php */
/* Location: ./system/application/libraries/Selector.php */