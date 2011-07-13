<?php
class Security_Model_Auth_Adapter 
	implements Zend_Auth_Adapter_Interface
{
	protected $_username;
	protected $_password;
	
	public function init($loginVO)
	{
		$this->_username = $loginVO->username;
		$this->_password = $loginVO->password;	
	}
	
	public function authenticate()
	{
		$adminMapper = App::getModel('security/auth/users/mapper');
		$where = "login='".$this->_username."' AND passwd='".md5($this->_password)."'";
		$entity = $adminMapper->find($where);
		if($entity instanceof CG_DomainModel_Abstract_Entity){
			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $entity->toVoEntity());
		}else{
			return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null);		
		}
	}
}
?>