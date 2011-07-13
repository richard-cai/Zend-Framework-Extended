<?php
class CG_Application_Module_Config
{
	private static $instance;
	
	protected $_options = array();
	
	protected $_environment;
	
	protected $_module;
	
	public function __construct()
	{
		if(isset(self::$instance))
			throw new CG_Application_Exception('Application module config instance already initialized.');
	}
	
	public function setEnvironment($environment)
	{
		$this->_environment = $environment;
		return $this;
	}
	
	public function setModule($module)
	{
		$this->_module = $module;
		return $this;
	}
	
	public function getInstance()
	{
		if(!isset(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function __get($key)
	{
		if(!array_key_exists($this->_module, $this->_options)){
			if(App::isAdmin()){
				$fullpath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' 
	              			. DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . strtolower($this->_module)
	              			. DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'module.ini';
			}else{
	            $fullpath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' 
	              			. DIRECTORY_SEPARATOR . 'front' . DIRECTORY_SEPARATOR . strtolower($this->_module)
	              			. DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'module.ini';
			} 			
            $config = $this->_loadOptions($fullpath);
            $this->_options[$this->_module] = $config; 			
		}
		return $this->_options[$this->_module]->$key;
	}
	
	
	/**
	* Load the config file
	* 
	* @param string $fullpath
	* @return array
	*/
	protected function _loadOptions($fullpath) 
	{
	    if (file_exists($fullpath)) {
                $cfg = new Zend_Config_Ini($fullpath, $this->_environment);
	    } else {
	            throw new CG_Application_Exception($fullpath. ' does not exist');
	    }
	    return $cfg;
	}
}