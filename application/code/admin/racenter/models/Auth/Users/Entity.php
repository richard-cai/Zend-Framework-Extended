<?php
class RaCenter_Model_Auth_Users_Entity
	extends CG_DomainModel_Abstract_Entity
{
	protected $_voEntityClass = 'Racenter_Model_Auth_Vo_SessionVO';
	
	protected $_data = array('login'=>'',
							 'name'=>'',
							 'groupID'=>'');
	
	public function toVoEntity()
	{
		$vo = parent::toVoEntity();
		$vo->sessionTime = time();
		switch($this->_data['groupID']){
			case '1':
				$department = 'adminstrator';
				break;
			case '4':
				$technicians = array('donald','donaldkao','john','andy','allen');
				if(in_array($this->_data['login'],$technicians)){
					$department = 'technician';
				}else{
					$department = 'customer-service';
				}
				break;
			default:
				$department = 'others';
				break;
		}
		$vo->department = $department;
		return $vo;	
	}
}
?>