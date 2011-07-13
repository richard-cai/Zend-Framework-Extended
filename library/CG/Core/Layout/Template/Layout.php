<?php
class CG_Core_Layout_Template_Layout
{
	protected $_layout;
	protected $_layoutClass = 'pages/layout';
	
	private static $instance;
	
	public function getInstance()
	{
		if(!isset(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function setLayout($layout)
	{
		$this->_layout = $layout;
		return $this;
	}
	
	public function getLayout()
	{
		return $this->_layout;
	}
	
	public function setLayoutClass($layoutClass)
	{
		$this->_layoutClass = $layoutClass;
		return $this;
	}
	
	public function getLayoutClass()
	{
		return $this->_layoutClass;
	}
}