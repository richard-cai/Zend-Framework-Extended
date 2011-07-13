<?php
class Admin_Model_RaCenter_VO_RequestVO
	implements Core_Amf_Object_Interface
{
	public $id;
	public $priority;
	public $recordNum;
	public $sku;
	public $partlist;
	public $remark;
	public $issueDate;
	public $finishDate;
	public $customerID;
	public $owner;
	public $partCharge;
	public $type;
	public $buyerPaid;
}
?>