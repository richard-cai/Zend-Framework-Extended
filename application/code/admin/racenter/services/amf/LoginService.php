<?php
class LoginService
{
	/**
	 * login into the system and get user identity
	 * @param Racenter_Model_Auth_VO_LoginVO $loginVO
	 * @return bool/Racenter_Model_Auth_VO_SessionVO
	 */
	public function login($loginVO)
	{
		$auth = Zend_Auth::getInstance();
		$adapter = new Racenter_Model_Auth_Adapter($loginVO);
		$result = $auth->authenticate($adapter);
		if($result->getCode() == Zend_Auth_Result::SUCCESS){
			return $result->getIdentity();
		}else{
			return false;
		}
	}
}
?>