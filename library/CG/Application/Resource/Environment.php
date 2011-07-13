<?php
class CG_Application_Resource_Environment
	extends Zend_Application_Resource_ResourceAbstract
{
	public function init()
	{	
		$this->_setEnvironment();
	}		
	
	public function _setEnvironment()
	{
		$environment = $this->getBootstrap()->getEnvironment();
		$config = CG_Application_Module_Config::getInstance();
		$config->setEnvironment($environment);
	}
}