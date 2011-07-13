<?php
abstract class CG_DomainModel_Abstract_Collection
	implements Countable,Iterator
{
	// mapper class
	protected $_gateway = null;
	
	// entity class
	protected $_entityClass;
	
	// collection array
	protected $_collection = array();
	
	/**
	 * 
	 * intialise object
	 * @param mixed $items
	 * @param CG_DomainModel_Abstract_Mapper $mapper
	 */
	public function __construct($items,$gateway)
	{
		$this->setGateway($gateway);
		if(!empty($items))
			$this->setCollection($items);
	}
	
	/**
	 * 
	 * set mapper class
	 * @param CG_DomainModel_Abstract_Mapper $mapper
	 */
	public function setGateway($gateway)
	{
		$this->_gateway = $gateway;
		return $this;
	}
	
	/**
	 * 
	 * get mapper class
	 */
	public function getGateway()
	{
		return $this->_gateway;
	}
	
	/**
	 * 
	 * set collection array
	 * @param array / Zend_Db_Table_Rowset $items
	 */
	public function setCollection($items)
	{
		if($items instanceof Zend_Db_Table_Rowset_Abstract){
			$items = $items->toArray();
		}
		$this->_collection = $items;
		return $this;
	}
	
	/**
	 * 
	 * get collection
	 */
	public function getCollection()
	{
		return $this->_collection;
	}

	/**
	 * (non-PHPdoc)
	 * @see Countable::count()
	 */
	public function count()
	{
		return count($this->_collection);
	}
	
	public function current()
	{
		$key = key($this->_collection);
		
		if ($key === null)
			return false;
			
		$item = $this->_collection[$key];
		
		if ((get_class($item) != $this->_entityClass) && !is_subclass_of($item, $this->_entityClass))
		{
			$ModelClass = $this->_entityClass;
			$item = new $ModelClass($item, $this->getGateway());
			$this->_collection[$key] = $item;
		}
    	return $item;
	}
	
	public function key()
	{
		return key($this->_collection);
	}
	
	public function next()
	{
		return next($this->_collection);
	}
	
	public function rewind()
	{
		return reset($this->_collection);
	}
	
	public function valid()
	{
		return $this->key() !== null;
	}
	
	public function append($item)
	{
		$this->_collection[] = $item;
	}
	
	/**
	 * 
	 * convert current collection to array
	 */
	public function toArray()
	{
		return $this->_toArray($this->_collection);
	}
	
	public function _toArray($pCollection)
	{
	    $collection = array();
	    foreach ($pCollection as $item) {
	      if ($item instanceof CG_DomainModel_Abstract_Entity)
	        $collection[] = $item->toArray();
	      else
	        $collection[] = $item;      
	    }
	
	    return $collection;
	}
	
	public function save()
	{
		return $this->getGateway()->save($this);
	}
	
	public function update($data = null, $where = null)
	{
		if(is_null($data) && is_null($where)){
			$count = 0;
			foreach($this as $entity){
				if($entity->update()){
					$count++;
				}
			}
		}else if(!is_null($data) && !is_null($where)){
			$this->getGateway()->update($data, $where);
		}else{
			throw new CG_DomainModel_Exception('Set correct parameters for collection update');
		}
		
		return $count;
	}
}
?>