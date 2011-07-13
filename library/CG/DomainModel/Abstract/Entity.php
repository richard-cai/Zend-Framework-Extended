<?php
abstract class CG_DomainModel_Abstract_Entity
{
	protected $_data = array();
	
	protected $_voEntityClass;
	
	protected $_gateway = null;
	
	//for lazy loading
	protected $_references = array();
	
	public function __construct($data, $gateway)
	{
		$this->setGateway($gateway);
		if(!empty($data))
			$this->setDataFrom($data);
	}
	
	public function setGateway($gateway)
	{
		$this->_gateway = $gateway;
		return $this;
	}
	
	public function getGateway()
	{
		return $this->_gateway;
	}
	
	// magic set method
	public function __set($name, $value)
	{
		if (!array_key_exists($name, $this->_data)) {
			throw new CG_Exception('You cannot set new properties ' .$name. ' on this object');
		}
		$this->_data[$name] = $value;
	}
	
	// magic get method
	public function __get($name)
	{
		switch ($name) {
			case 'gateway':
				return $this->getGateway();
				break;
			default:
				if (array_key_exists($name, $this->_data)) 
					return $this->_data[$name];
				else
					throw new CG_DomainModel_Exception(" Invalid attribute - ".$name);
				break;
		}
	}
	
	// magic isset method 
	public function __isset($name)
	{
		return isset($this->_data[$name]);
	}
	
	// magic unset method
	public function __unset($name)
	{
		if (isset($this->_data[$name])) {
			unset($this->_data[$name]);
		}
	}
	
	/*
	 * Import data from another source
	 * @param Array $array
	 */
	public function setDataFrom($data)
	{
		if(is_object($data)){
			foreach($this->_data as $key => $value){
				if(isset($data->$key))
					$this->_data[$key] = $data->$key;
			}
		}elseif(is_array($data)){
			foreach($this->_data as $key => $value){
				if(isset($data[$key]))
					$this->_data[$key] = $data[$key];
			}
		}
		return $this;
	}
	
	/**
	 * 
	 * get object reference for lazy loading 
	 * @param String $name
	 */
	public function getReferenceId($name)
	{
		if(isset($this->_references[$name]))
			return $this->_references[$name];
	}
	
	/**
	 * 
	 * set object reference for lazy loading
	 * @param String $name
	 * @param mixed $id
	 */
	public function setReferenceId($name,$id)
	{
		$this->_references[$name] = $id;		
	}
	
	/**
	 * 
	 * convert current model to array
	 */
	public function toArray()
	{
		return $this->_data;
	}
	
	/**
	 * 
	 * convert current model to voEntity
	 */
	public function toVoEntity()
	{
		$voClass = $this->_voEntityClass;
		$voObject = new $voClass();
		foreach($this->_data as $property => $value){
			if(isset($voObject->{$property}) || property_exists($voObject, $property)){
				$voObject->{$property} = $value;
			}
		}
		return $voObject;
	}
	
	public function save()
	{
		$key = $this->getPrimaryKey();
		$gateway = $this->getGateway();
		$this->_data[$key] = $gateway->save($this);
		return $this;
	}
	
	public function update()
	{
		if(func_num_args() <= 1){
			$key = $this->getPrimaryKey();
			$gateway = $this->getGateway();
			if(func_num_args() == 1){
				$data = func_get_arg(0);
				if(!isset($data[$key]))
					throw Racenter_Model_Request_Exception();
				else{
					$this->$key = $data[$key];	
					unset($data[$key]);				
				} 
			}else{
				$data = $this->toArray();
				unset($data[$key]);
			}
			if(!empty($data)){
				return $gateway->update($data,$key."=".$this->$key);
			}
		}
		return 0;
	}
	
	/**
	 * 
	 * get parimary key
	 * @return mix
	 */
	public function getPrimaryKey()
	{
		return key($this->_data);
	}
}
