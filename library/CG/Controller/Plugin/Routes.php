<?php
class CG_Controller_Plugin_Routes
	extends Zend_Controller_Plugin_Abstract
{
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{
		if($request->getParam('admin')){           
            $layout = Zend_Layout::getMvcInstance();
            $layout->setViewScriptPath(APPLICATION_PATH.'/design/backend/default/default/layout/');
            
            $view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
          
            $view->addScriptPath(APPLICATION_PATH.'/design/backend/default/default/scripts');
             
            // set autoloader
            $autoloader = Zend_Loader_Autoloader::getInstance(); 
            $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'admin';
            $iterator = new DirectoryIterator($path);
            $moduleLoaders = array();
            foreach($iterator as $folder){
            	$name = $folder->getFileName();
            	if($folder->isDir() && !$folder->isDot() && substr($name,0,1) != '.'){
            		$basePath = $path . DIRECTORY_SEPARATOR . $name;
            		$moduleLoaders[] = new CG_Application_Module_Autoloader(array(
            									'namespace' => ucfirst($name), 
            									'basePath'	=> $basePath ));
            	}
            }
            $autoloader->setAutoloaders($moduleLoaders);
        }
	}
	
	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{
        $front = Zend_Controller_Front::getInstance();
        $front->addModuleDirectory(APPLICATION_PATH.'/code/admin');
	}
}