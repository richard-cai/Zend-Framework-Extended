<?php
class Security_GatewayController
	extends CG_Controller_Action
{
	public function preDispatch()
	{
		//disable layout
	    $this->_helper->layout()->disableLayout();
    	
	    //disable view
	    $this->_helper->viewRenderer->setNoRender(true);
    	
	    //start session
	    Zend_Session::start();
	}
	
	public function indexAction()
	{			
		$server = new Zend_Amf_Server();
		
		//set amf server session namespace
		$server->setSession(Security_Model_Session_Manager::ADMIN_SESSION_NAME);
		
		$server->setProduction(false);
		
		//set service root directory
		$server->addDirectory(realpath(dirname(__FILE__) . '/../services/amf/'));
		
		//set value object class mapping
		$server->setClassMap('LoginVO', 'Security_Model_Auth_Vo_LoginVO');
		$server->setClassMap('SessionVO', 'Security_Model_Auth_Vo_SessionVO');
		
		echo($server->handle());
	}
}