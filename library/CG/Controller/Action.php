<?php
class CG_Controller_Action 
	extends Zend_Controller_Action
{	
	public function postDispatch()
	{
		$this->_setDefaultLayout();
	}
	
	protected function _setDefaultLayout()
	{
		$layout = $this->_helper->layout();
		// detect if layout is enabled
		if($layout->isEnabled()){
			$request = $this->getRequest();
			$script = $layout->getLayout();
			//set default layout script 
			if($script == 'layout' || $script == ''){
				$layout->setLayout($request->getModuleName() . DIRECTORY_SEPARATOR . $request->getControllerName() . DIRECTORY_SEPARATOR . $this->getRequest()->getActionName());
			}
		}
	}
	
	public function getOption($key)
	{
		$instance = CG_Application_Module_Config::getInstance();
		return $instance->setModule(App::getModuleName(get_class($this)))
				 		->$key;
	}
} 
?>