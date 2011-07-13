<?php
class Pages_Block_Element_Header
	extends CG_Core_Layout_Template_Block
{
	protected function preDispatch()
	{
		$this->headLink()->appendStylesheet( App::addCssPath('crazysales.css', true) );
		$this->headScript()->appendFile( App::addJsPath('jquery.js', true) );
		$this->headScript()->appendFile( App::addJsPath('jquery-plugins.js', true) );
	}
}