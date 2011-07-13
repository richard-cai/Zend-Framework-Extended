<?php
class CG_Controller_Plugin_Browser
	extends Zend_Controller_Plugin_Abstract
{
	public function dispatchLoopStartup($request)
	{
		$view = Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer')->view;
		$device = $view->getHelper('userAgent')->getUserAgent()->getDevice();
		if($device instanceof Zend_Http_UserAgent_Mobile){
			//reset view script base path
			$config = App::getConfig();
			$config['template']['defaulttheme'] = $config['template']['defaultmobiletheme'];
			$config['template']['defaultskin'] = $config['template']['defaultmobileskin'];
			App::setConfig($config);
			
			//reset layout script base path
			$layout = Zend_Layout::getMvcInstance();
            $layout->setViewScriptPath(APPLICATION_PATH.'/design/frontend/'.$config['template']['defaultmobilelayout'].'/layout/');
		}
	}
}