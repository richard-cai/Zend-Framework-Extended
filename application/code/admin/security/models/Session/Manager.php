<?php
class Security_Model_Session_Manager
	extends Core_Security_Session_Manager
{
	const ADMIN_SESSION_NAME = 'ADMIN';
	const ADMIN_SESSION_PREFIX = 'ADMIN';
	
	protected static $instance;
	
	public function __construct()
	{
		if(isset(self::$instance))
			throw new Security_Model_Exception(__CLASS__. 'is already initialized.');
	}
	
	public static function getInstance()
	{
		if(!isset(self::$instance))
			self::$instance = new self();
		return self::$instance;			
	}
	
	public function get($name,$singleInstance = false)
	{
		return new Zend_Session_Namespace(self::ADMIN_SESSION_PREFIX.'_'.$name, $singleInstance);
	}
	
	public function setIdentity($identity)
	{
		$this->get(self::ADMIN_SESSION_NAME, true)->identity = $identity;
		return $this;
	}
	
	public function getIdentity()
	{
		$this->get(self::ADMIN_SESSION_NAME, true)->identity;
	}
}