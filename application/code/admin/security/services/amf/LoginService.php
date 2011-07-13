<?php
class LoginService
{
	
	public function initAcl()
	{
		
	}
	
	/**
	 * login into the system and get user identity
	 * @param Security_Model_Auth_VO_LoginVO $loginVO
	 * @return bool/Security_Model_Auth_VO_SessionVO
	 */
	public function login($loginVO)
	{
		// authenticate user
		$auth = Zend_Auth::getInstance();
		$adapter = App::getModel('security/auth/adapter');
		$adapter->init($loginVO);
		$result = $auth->authenticate($adapter);
		if($result->getCode() == Zend_Auth_Result::SUCCESS){
			$identity = $result->getIdentity();
			
			// set login status
			$sm = App::getInstance('security/session/manager');
			$sm->setIdentity($identity);
			
			return $identity;
		}else{
			return false;
		}
	}
}
?>