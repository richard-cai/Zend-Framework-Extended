<?php
class CG_Application_Resource_Config
	extends Zend_Application_Resource_ResourceAbstract
{
	public function init()
	{
		$this->_setAppConfig();
	}
	
	protected function _setAppConfig()
	{
		$config = $this->getBootstrap()->getApplication()->getOptions();
		App::setConfig($config);
	}
}