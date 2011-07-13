<?php
class CG_View_Helper_Block
{
	public $view;
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
	
	public function block($blockName,$template,$arguments = array(),$method = 'toHtml',$first = true)
	{	
		if(strlen($blockName) > 0){
			$blockName = App::getBlockClassName($blockName);
			
			if (class_exists($blockName)) {
				$block = new $blockName();
				
				$moduleName = substr($blockName,0,strpos($blockName,'_'));
				
				//set module config module
				$config = CG_Application_Module_Config::getInstance();
				$config->setModule($moduleName);
				
				//add helpers for block
				$this->view->addHelperPath(App::getModuleViewHelperPath($moduleName), ucfirst($moduleName).'_View_Helper');
				//add filters for block
				$this->view->addFilterPath(App::getModuleViewFilterPath($moduleName), ucfirst($moduleName).'_View_Filter');
				//add javascript path
				$this->view->setScriptPath(App::getModuleScriptPath(App::getBlockType($blockName)));
				
				//detect template dead circular
				if($first)
					CG_Core_Layout_Template_Block::$blocks = array($template);
					
				return $block->setRequest(Zend_Controller_Front::getInstance()->getRequest())
							 ->setView($this->view)
							 ->setTemplate($template)
				   			 ->$method($arguments);
			}else{
				$msg = $blockName ." doesn't exist.";
			}
		}else{
			$msg = "Specify block name for current block.";
		}
		throw new CG_View_Exception($msg);
	}
}