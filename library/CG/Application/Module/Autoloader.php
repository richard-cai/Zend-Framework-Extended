<?php
class CG_Application_Module_Autoloader
	extends Zend_Application_Module_Autoloader
{
	public function initDefaultResourceTypes()
	{	
		$basePath = $this->getBasePath();
        $this->addResourceTypes(array(
            'form'    => array(
                'namespace' => 'Form',
                'path'      => 'forms',
            ),
            'model'   => array(
                'namespace' => 'Model',
                'path'      => 'models',
            ),
            'plugin'  => array(
                'namespace' => 'Plugin',
                'path'      => 'plugins',
            ),
            'service' => array(
                'namespace' => 'Service',
                'path'      => 'services',
            ),
            'viewhelper' => array(
                'namespace' => 'View_Helper',
                'path'      => 'views/helpers',
            ),
            'viewfilter' => array(
                'namespace' => 'View_Filter',
                'path'      => 'views/filters',
            ),
            'block'		 => array(
            	'namespace' => 'Block',
            	'path'		=> 'blocks',
            ),
        ));
        $this->setDefaultResourceType('model');
	}
	
}