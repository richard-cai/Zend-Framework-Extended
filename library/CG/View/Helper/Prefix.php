<?php
class CG_View_Helper_Prefix
{
	protected $request;
	
	public function __construct()
	{
		if(null == $this->request){
			$this->request = Zend_Controller_Front::getInstance()->getRequest(); 
		}
	}
	
	public function prefix($key)
	{	
		if($this->request->getParam('admin')){
			return md5($this->request->getParam('admin').'/'.$this->request->getModuleName().'/'.$this->request->getControllerName().'/'.$this->request->getActionName().'/'.$key);
		}else{
			return md5($this->request->getModuleName().'/'.$this->request->getControllerName().'/'.$this->request->getActionName().'/'.$key);
		}
	}
}