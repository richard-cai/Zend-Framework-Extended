<?php
class CG_Controller_Plugin_Common
	extends Zend_Controller_Plugin_Abstract
{
	public function dispatchLoopShutdown()
	{
		$layoutBroker = CG_Core_Layout_Template_Layout::getInstance();
		$layout = $layoutBroker->getLayout();
		if(isset($layout)){
			$response = $this->getResponse();
			$view = Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer')->view;
			$content = $view->block($layoutBroker->getLayoutClass(),$layout,array('content'=>$response->getBody()));
			$response->setBody($content);
		}
	}
}