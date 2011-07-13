<?php
abstract class CG_DomainModel_Abstract_Mapper
{
	//table gateway
	protected $_tableGateway = null;
	
	// Domain model entity class
	protected $_entityClass;
	
	// Domain model Collection class
	protected $_collectionClass;
	
	//prevent loading twice for the same object
	protected $_identityMap = array();
	
	protected $_tableName;
	
	/**
	 * 
	 * initialise mapper
	 * @param Zend_Db_Table_Abstract $tableGateway
	 */
	public function __construct(Zend_Db_Table_Abstract $tableGateway = null)
	{
		if (is_null($tableGateway)) {
			$this->_tableGateway = new Zend_Db_Table($this->_tableName);
		} else {
			$this->_tableGateway = $tableGateway;
		}
	}
	
	/**
	 * 
	 * get data gateway
	 * @return Zend_Db_Table_Abstract
	 */
	public function getGateway()
	{
		return $this->_tableGateway;
	}
	
	/**
	 * 
	 * set entity class for current model
	 * @param String/CG_DomainModel_Abstract_Entity $class
	 * @return this
	 */
	public function setEntityClass($class)
	{
		if (is_string($class)){
			$this->_entityClass = $class;
		} elseif ($class instanceof CG_DomainModel_Abstract_Entity){
			$this->_entityClass = get_class($class);	
		}
		return this;
	}
	
	/**
	 * 
	 * get single entity instance
	 * @return CG_DomainModel_Abstract_Entity
	 */
	public function getEntity()
	{
		$entity = $this->getEntityClass();
		return new $entity(array(),$this);
	}
	
	/**
	 * 
	 * get entity class for current model
	 * @return String
	 */
	public function getEntityClass()
	{
		return $this->_entityClass;
	}
	
	/**
	 * 
	 * set collection class for current model
	 * @param String/CG_DomainModel_Abstract_Collection $class
	 * @return this
	 */
	public function setCollection($class)
	{
		if (is_string($class)){
			$this->_entityClass = $class;
		} elseif ($class instanceof CG_DomainModel_Abstract_Collection) {
			$this->_entityClass = get_class($class);
		}
		return $this;
	}
	
	/**
	 * get collection instance
	 * @return CG_DomainModel_Abstract_Collection
	 */
	public function getCollection()
	{
		$collection = $this->getCollectionClass();
		return new $collection(array(),$this);
	}
	
	/**
	 * get collection class 
	 * @return String
	 */
	public function getCollectionClass()
	{
		return $this->_collectionClass;	
	}
	
	/**
	 * 
	 * get entity id
	 * @param unknown_type $id
	 */
	protected function _getIdentity($id)
	{
		if (array_key_exists($id, $this->_identityMap)) {
			return $this->_identityMap[$id];
		}
	}
	
	/**
	 * 
	 * set entity id
	 * @param String $id
	 * @param CG_DomainModel_Abstract_Entity $entity
	 */
	protected function _setIdentity($id, CG_DomainModel_Abstract_Entity $entity)
	{
		$this->_identityMap[$id] = $entity;
	}
	
	/**
	 * 
	 * process data before save
	 * @param array $data
	 */
	protected function _preSave($object, $bool)
	{
		if($bool){
			$entity = $object->current();
			$data = $object->toArray();
			$key = $entity->getPrimaryKey();
			$func = create_function('$a','unset($a[\''.$key.'\']); return $a;');
			$data = array_map($func, $data);
		}else{
			$data = $object->toArray();
			$key = $object->getPrimaryKey();
			unset($data[$key]);	
		}
		return $data;
	}
	
	/*
	 * update or insert entity object 
	 */
	public function save($mix)
	{
		if($mix instanceof $this->_entityClass){
			$data = $this->_preSave($mix, false);
			return $this->getGateway()->insert($data);
		}else if($mix instanceof $this->_collectionClass){
			$adapter = $this->getGateway()->getAdapter();
			// save memory
			$mix = $this->_preSave($mix, true);
			$sql = 'INSERT INTO '.$this->_tableName.'(';
			$index = 0;
			foreach($mix as &$entity){
				if($index == 0){
					$sql .= implode(",", array_keys($entity)).") VALUES";
				}
				$tmp = '(';
				foreach($entity as $key => $value){
					$tmp .= $adapter->quote($value).",";
				}
				$tmp = substr($tmp, 0, -1)."),";
				
				$sql .= $tmp;
				$index++;
			}
			$sql = substr($sql, 0, -1);
			return $adapter->getConnection()->exec($sql);
		}
	}
	
	/*
	 * delete entity objects
	 */
	public function delete($mix)
	{
		return $this->_tableGateway->delete($mix);
	}
	
	/**
	 * fetch entities
	 */
    public function fetch($mix = null, $sort = null, $limit = null, $offset = null)
    {
    	$entries = $this->_tableGateway->fetchAll($mix, $sort, $limit, $offset);
		return $this->getCollection()->setCollection($entries);
    }
	
	/**
	 * find entity
	 */
	public function find($mix)
	{
		$entity = $this->_tableGateway->find($mix);
		if($entity instanceof Zend_Db_Table_Row){
			$cls = $this->_entityClass;
			return new $cls($entity->toArray(), $this);			
		}else if($entity instanceof Zend_Db_Table_Rowset){
			return $this->getCollection()->setCollection($entity);
		}
		return false; 
	}
	
	/**
	 * update entity
	 */
	public function update($data, $where)
	{
		return $this->_tableGateway->update($data, $where);
	}
}
?>