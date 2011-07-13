<?php
class CG_Core_Cache_Translate
{
	protected static $_instance;
	
	protected $_adapter;
	
	public function __construct()
	{
		if(isset(self::$_instance))
			throw new CG_Core_Exception(__CLASS__ . " instance has been initialized.");
	}
	
	public function getInstance()
	{
		if(!isset(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function setAdapter()
	{
		
	}
	
	public function getAdapter()
	{
		if(null === $this->_adapter)
			throw new CG_Core_Exception('adapter not exists.');
			
		return $this->_adapter;
	}
	
	
}