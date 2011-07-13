<?php
class CG_View_Helper_Cache
{
	public function cache($lifetime, $cacheMethod = 'File')
	{
		return App::getCache('Output', $cacheMethod, array('lifetime'=>$lifetime));
	}
}