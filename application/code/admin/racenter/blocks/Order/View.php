<?php
class Racenter_Block_Order_View
	extends CG_Core_Layout_Template_Block
{
	public function getOrders()
	{
		return  App::getSingleton('home/slide', 'block')->getSlides();
	}
	
	public function getSlides()
	{
		return App::getSingleton('home/slide', 'block')->getSlides();
	}
}