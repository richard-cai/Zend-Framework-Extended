<?php
class App
{
	/**
     * Registry collection
     *
     * @var array
     */
    private static $_registry = array();
    
    private static $_config;
    
    private static $_isAdmin;
    

	public static function autoVersion($file)
	{
		$index = strpos($file, '.');
		$filename = substr($file, 0, $index);
		$filetype = substr($file, $index+1);
		$version = filemtime(realpath(APPLICATION_PATH.'/../public').$file);
		return $filename.".".$version.".".$filetype;
	}
	
	public static function addCssPath($file, $bool = false)
	{
		$path = self::$_isAdmin?'/backend/':'/frontend/';
		$path = '/skins'.$path.self::$_config['template']['defaulttheme'].'/css/'.$file;
		if($bool)
			$path = self::autoVersion($path);
		return $path;
	}
	
	public static function addJsPath($file, $bool = false)
	{
		$path = self::$_isAdmin?'/backend/':'/frontend/';
		$path = '/skins'.$path.self::$_config['template']['defaultskin'].'/js/'.$file;
		if($bool)
			$path = self::autoVersion($path);
		return $path;
	}
	
	public static function addImagePath($file, $bool = false)
	{
		$path = self::$_isAdmin?'/backend/':'/frontend/';
		$path = '/skins'.$path.self::$_config['template']['defaultskin'].'/images/'.$file;
		if($bool)
			$path = self::autoVersion($path);
		return $path;
	}

	public static function addFlashPath($path, $bool = false)
	{
		$path = '/media/flashes/'.$path;
		if($bool)
			$path = self::autoVersion($path);
		return $path;
	}
	
	public static function addMediaImagePath($path, $bool = false)
	{
		$path = '/media/images/'.$path;
		if($bool)
			$path = self::autoVersion($path);
		return $path;
	}
	
	public static function addVideoPath($path, $bool = false)
	{
		$path = '/media/video/'.$path;
		if($bool)
			$path = self::autoVersion($path);
		return $path;
	}
    
    public static function setConfig($config)
    {
    	self::$_config = $config;
    }
    
    public static function getBlockType($blockName)
    {
    	$ws = explode('_', $blockName);
    	return $ws[2];
    }
    
    public static function getModuleName($className)
    {
    	$ws = explode('_', $className);
    	return $ws[0];
    }
    
    public static function getModuleViewHelperPath($module)
    {
    	$dir = (App::isAdmin())?'admin':'front';
    	return APPLICATION_PATH. DIRECTORY_SEPARATOR . 'code' 
    			. DIRECTORY_SEPARATOR . $dir 
    			. DIRECTORY_SEPARATOR . strtolower($module) . DIRECTORY_SEPARATOR . 'views' 
    			. DIRECTORY_SEPARATOR .'helpers'; 
    }
    
	public static function getModuleViewFilterPath($module)
    {
    	$dir = (App::isAdmin())?'admin':'front';
    	return APPLICATION_PATH. DIRECTORY_SEPARATOR . 'code' 
    			. DIRECTORY_SEPARATOR . $dir 
    			. DIRECTORY_SEPARATOR . strtolower($module) . DIRECTORY_SEPARATOR . 'views' 
    			. DIRECTORY_SEPARATOR .'filters'; 
    }
    
	public static function getModuleScriptPath($type)
    {
    	$config = self::getConfig();
    	$theme = $config['template']['defaulttheme'];
    	$dir = (App::isAdmin())?'backend':'frontend';
    	switch ($type) {
    		case 'Script':
    			$path = 'js';
    			break;
    		default:
    			$path = 'scripts';
    			break;
    	}
    	return APPLICATION_PATH . DIRECTORY_SEPARATOR . 'design' 
    		   	. DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR. $path; 
    }
    
	public static function isAdmin()
	{
		if(!isset(self::$_isAdmin)){
			$request = Zend_Controller_Front::getInstance()->getRequest();
			self::$_isAdmin = ($request->getParam('admin'))?true:false;
		}
		return self::$_isAdmin;
	}
    
    public static function getConfig()
    {
    	return self::$_config;
    }
    
    //@todo implements store configration
    public static function getStoreConfig()
    {
    	
    }
    
    //@todo detect user browser information
    public static function getUserBrowserConfig()
    {
    	
    }
    
	/**
     * Register a new variable
     *
     * @param string $key
     * @param mixed $value
     * @param bool $graceful
     */
    public static function register($key, $value, $graceful = false)
    {
        if(isset(self::$_registry[$key])) {
            if ($graceful) {
                return;
            }
            Crazy::throwException('Crazy registry key "'.$key.'" already exists');
        }
        self::$_registry[$key] = $value;
    }

    public static function unregister($key)
    {
        if (isset(self::$_registry[$key])) {
            if (is_object(self::$_registry[$key]) && (method_exists(self::$_registry[$key],'__destruct'))) {
                self::$_registry[$key]->__destruct();
            }
            unset(self::$_registry[$key]);
        }
    }

    /**
     * Retrieve a value from registry by a key
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        if (isset(self::$_registry[$key])) {
            return self::$_registry[$key];
        }
        return null;
    }
    
    public static function has($key)
    {
    	return array_key_exists($key, self::$_registry);
    }
    
    public static function getModel($className = '', $arguments = array())
    {
    	$class = self::getModelClassName($className);
    	if(count($arguments)){
    		$reflect = new ReflectionClass($class);
    		return $reflect->newInstanceArgs($arguments);
    	}else{
    		return new $class;
    	}
    }
    
    public static function getInstance($className = '')
    {
    	$class = self::getModelClassName($className);
    	return call_user_func_array(array($class,'getInstance'));
    }
    
    public static function getSingleton($className = '', $type = 'model', $arguments = array())
    {
    	$type = ucfirst($type);
    	$registryKey = 'app/singleton/'.$type.'/'.$className;
    	if(!App::has($registryKey)){
    		switch ($type){
    			case 'Model':
    				App::register($registryKey, self::getModel($className, $arguments));
    				break;
    			case 'Block':
    				App::register($registryKey, self::getBlock($className, $arguments));
    				break;
    			default:
    				self::throwException($type . ' no supported in the application.');
    				break;
    		}
    	}
    	return App::get($registryKey);
    }
    
    public static function getBlock($className = '', $arguments = array())
    {
    	$class = self::getBlockClassName($className);
    	if(count($arguments)){
    		return new $class($arguments);
    	}else{
    		return new $class;
    	}
    }
    
    public static function getBlockClassName($className)
    {
    	return self::getClassName($className, 'block');
    }
    
    public static function getModelClassName($className)
    {
    	return self::getClassName($className, 'model');
    }
    
    protected static function getClassName($className, $type)
    {
    	$className = str_replace(array('/','_'), ' ', $className);
		$ws = explode(' ', $className); 
		$className = '';
		$length = count($ws);
		for ($i = 0; $i < $length; $i++) {
			$className .= ucfirst($ws[$i]);
			if ($i != $length - 1) {
				$className .= '_';
			} 
			if ($i == 0) {
				$className .=ucfirst($type).'_';
			}
		}
		return $className;
    }
    
    public static function getCache($frontend, $backend, $frontendOptions = array(), $backendOptions = array(), $customFrontendNaming = false, $customBackendNaming = false, $autoload = false)
    {
    	$arguments = func_get_args();
    	$lifetime = $arguments[2]['lifetime'];
    	unset($arguments[2]['lifetime']);
    	$key = 'app/cache/' . serialize($arguments);
    	if(!self::has($key)){
    		$config = self::getConfig();
    		switch($frontend){
    			case 'Core':
    				$frontendOptions['automatic_serialization'] = isset($frontendOptions['automatic_serialization'])?$frontendOptions['automatic_serialization'] : $config['cache']['frontend']['core']['automatic_serialization'];
    				break;
    		}
	    	switch($backend){
	    		case 'File':
	    			$prefix = realpath(APPLICATION_PATH. DIRECTORY_SEPARATOR . '..' .$config['cache']['backend']['file']['dir'].strtolower($frontend));
	    			if(isset($backendOptions['cache_dir'])){
	    				$backendOptions['cache_dir'] = $prefix . $backendOptions['cache_dir'];
	    			}else{
	    				$backendOptions['cache_dir'] = $prefix;
	    			}
	    			break;
	    		case 'Memcached':
					$backendOptions['servers']= $config['cache']['backend']['memcached']; 
	    			break;
	    	}
	    	$cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions, $customFrontendNaming, $customBackendNaming, $autoload);
	    	self::register($key, $cache);
    	}
    	$cache = self::get($key);
    	$cache->setLifetime($lifetime);
    	return $cache;
    }
    
	public static function throwException($message, $messageStorage=null)
    {
        if ($messageStorage && ($storage = Crazy::getSingleton($messageStorage))) {
            $storage->addError($message);
        }
        throw new CG_Core_Exception($message);
    }
}