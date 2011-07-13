<?php
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class ControllerTestCase 
	extends Zend_Test_PHPUnit_ControllerTestCase
{
	/**
	 * @var Zend_Application
	 */
	protected $application;
	
	public function setUp()
	{
		$this->bootstrap = array($this,'appBootstrap');
		parent::setUp();
	}
	
	public function appBootstrap()
	{
		$this->application = new Zend_Application(APPLICATION_ENV,APPLICATION_PATH . '/configs/application.ini');
		$this->application->bootstrap();
		
        /**
         * Fix for ZF-8193
         * http://framework.zend.com/issues/browse/ZF-8193
         * Zend_Controller_Action->getInvokeArg('bootstrap') doesn't work
         * under the unit testing environment.
         */
		$front = Zend_Controller_Front::getInstance();
        if($front->getParam('bootstrap') === null) {
            $front->setParam('bootstrap', $this->application->getBootstrap());
        }
		
	}
}
?>