<?php
abstract class CG_Core_Layout_Template_Block_Abstract
{
	protected $_request;
	protected $_view;
	protected $_template;
	
	public static $blocks = array();
	
	public function addCssPath($file)
	{
		$config = App::getConfig();
		//can be used to build theme hirachy
		return '/skins/'.$config['template']['defaulttheme'].'/css/'.$file;
	}
	
	public function addJsPath($file)
	{
		$config = App::getConfig();
		//can be used to build template hirachy
		return '/skins/'.$config['template']['defaultskin'].'/js/'.$file;
	}
	
	public function addImagePath($file)
	{
		$config = App::getConfig();
		return '/skins/'.$config['template']['defaultskin'].'/images/'.$file;
	}

	public function addFlashPath($path)
	{
		return '/media/flashes/'.$path;
	}
	
	public function addMediaImagePath($path)
	{
		return '/media/images/'.$path;
	}
	
	public function addVideoPath($path)
	{
		return '/media/video/'.$path;
	}
	
	public function _($text)
	{
		//get current block module name
		$ws = explode('_', get_class($this));
		$module = strtolower($ws[0]);
		
		if(!Zend_Registry::isRegistered('Zend_Translate')){
			$config = App::getConfig();
			$language = $config['resources']['locale']['default'];
			//@todo add module tranlation files
			$translate = new Zend_Translate(
								array('adapter' => 'csv', 
									  'content' => APPLICATION_PATH.'/design/frontend/'.$config['template']['defaulttheme']."/locale/$language/$module.csv",
									  'delimiter' => ','));
			//@todo add cache
			Zend_Registry::set('Zend_Translate', $translate);
		}
		$translate = Zend_Registry::get('Zend_Translate');
		return $translate->_($text);
	}
	
	public function autoVersion($file)
	{
		$index = strpos($file, '.');
		$filename = substr($file, 0, $index);
		$filetype = substr($file, $index+1);
		$version = filemtime(realpath(APPLICATION_PATH.'/../public').$file);
		return $filename.".".$version.".".$filetype;
	}
	
	public function option($key)
	{
		return CG_Application_Module_Config::getInstance()->$key;
	}
	
	public function setView(Zend_View_Interface $view)
	{
		$this->_view = $view;
		return $this;
	}
	
	public function setRequest(Zend_Controller_Request_Abstract $request)
	{
		$this->_request = $request;
		return $this;
	}
	
	public function getRequest()
	{
		return $this->_request;
	}
	
	public function getTemplate()
	{
		return $this->_template;
	}
	
	public function setTemplate($template)
	{
		$this->_template = $template;
		return $this;
	}
	
	public function getTemplatePath()
	{
		$paths = $this->_view->getScriptPaths();
		$file = current($paths) . $this->getTemplate();
		if(is_readable($file)){
			return $file;
		}
		throw new CG_Core_Layout_Exception("template not found.");
	}
	
	public function __call($name, $arguments)
	{
		if(method_exists($this,$name)){
			return $this->$name(implode(",", $arguments));
		}else{
			if(!isset($this->_view)){
				$this->_view = Zend_Controller_Action_HelperBroker::getExistingHelper( 'viewRenderer' )->view;
			}
			$helper = $this->_view->getHelper($name);
			return call_user_func_array(
	            array($helper, $name),
	            $arguments
	        );
		}
	}
	
	public function block($blockName, $template, $arguments = array(), $method = 'toHtml')
	{
		if(in_array($template,self::$blocks)){
			throw new CG_Core_Layout_Exception("template dead circular detected.");
		}else{
			array_push(self::$blocks,$template);
		}
		return $this->_view->block($blockName, $template, $arguments, $method, false);
	}
	
	protected function preDispatch()
	{
		
	}
	
	protected function postDispatch()
	{
		
	}
	
	public function toHtml($arguments = null)
	{
		if(!is_null($arguments)){
			extract($arguments);
		}
				
		$template = $this->getTemplatePath();
		
		$this->preDispatch();
		
		include $template;
		
		$this->postDispatch();
		
		// remove block tracking
		self::$blocks = array();
	}
}