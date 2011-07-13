<?php
class CG_Application_Resource_Locale
	extends Zend_Application_Resource_ResourceAbstract
{
	public function init()
	{
		$this->_getLocale();
	}
	
	public function _getLocale()
	{
		$options = $this->getBootstrap()->getOptions();
		$locale = new Zend_Locale($options['resources']['locale']['default']);
		Zend_Registry::set('Zend_Locale', $locale);
	}
}