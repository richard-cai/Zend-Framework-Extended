<?php
class Category_View_Helper_Escape
{
	public function escape($value)
	{
		return $value .= "category";
	}
}