<?php
class CG_Application_Module_Bootstrap
	extends Zend_Application_Module_Bootstrap
{
	public function initResourceLoader()
	{
		$namespace = $this->getAppNamespace();
		$r = new ReflectionClass($this);
        $path = $r->getFileName();
        $this->setResourceLoader(new CG_Application_Module_Autoloader(array(
             'namespace' => $namespace,
             'basePath'  => dirname($path),
        )));
	}
}
?>