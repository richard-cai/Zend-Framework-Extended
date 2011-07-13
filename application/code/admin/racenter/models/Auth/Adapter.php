<?php
class RaCenter_Model_Auth_Adapter 
	implements Zend_Auth_Adapter_Interface
{
	protected $username;
	protected $password;
	
	public function __construct($loginVO)
	{
		$this->username = $loginVO->username;
		$this->password = $loginVO->password;	
	}
	
	public function authenticate()
	{
		$adminMapper = new Racenter_Model_Auth_Users_Mapper();
		$where = "login='".$this->username."' AND passwd='".md5($this->password)."'";
		$entity = $adminMapper->find($where);
		if($entity instanceof CG_DomainModel_Abstract_Entity){
			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $entity->toVoEntity());
		}else{
			return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null);		
		}
	}
}
?>