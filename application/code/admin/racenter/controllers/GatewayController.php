<?php
class Racenter_GatewayController
	extends CG_Controller_Action
{
	public function preDispatch()
	{
	    $this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
	}
	
	public function indexAction()
	{			
		$acl = new Zend_Acl();
		$acl->addRole(new Zend_Acl_Role('administrator'));
		Zend_Session::regenerateId();
		
		$server = new Zend_Amf_Server();
		
		//set amf server session namespace
		$server->setSession();
		$server->setProduction(false);
		$server->setAcl($acl);
		
		//set service root directory
		$server->addDirectory(realpath(dirname(__FILE__) . '/../services/amf/'));
		
		echo($server->handle());
	}
}