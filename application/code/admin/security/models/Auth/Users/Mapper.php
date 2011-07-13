<?php
class Security_Model_Auth_Users_Mapper
	extends CG_DomainModel_Abstract_Mapper
{
	protected $_tableName = 'ss_admins';
	protected $_entityClass = 'Security_Model_Auth_Users_Entity';
	
	public function find($where)
	{
		$entry = $this->_tableGateway->fetchRow($where);
		if(!is_null($entry)){
			$cls = $this->_entityClass;
			$obj = new $cls;
			$obj->login = $entry->login;
			$obj->name = $entry->name;
			$obj->groupID = $entry->groupID; 
			return $obj;
		}else{
			return null;
		}
	}
	
	public function save($mix){}
	public function delete($mix){}
	public function fetch($mix){}
	
}
?>