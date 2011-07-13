<?php
class Home_Block_Slides_View 
	extends CG_Core_Layout_Template_Block
{
	public function test(){
		$request = $this->getRequest();
		return $request->getParam('key');
	}
	
	
}