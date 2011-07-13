<?php
class CG_Application_Resource_Acl
	extends Zend_Application_Resource_ResourceAbstract
{
    
    /**
     * Initialize
     *
     * @return Zend_Acl
     */
    public function init()
    {
        return $this->_getAcl();
    }
    
    /**
     * Load the module's acl
     *
     * @return Zend_Acl
     */
    protected function _getAcl()
    {
        // Load the acl from the registry if it doesn't exist
        if (Zend_Registry::isRegistered('Zend_Acl')) {
            $acl = Zend_Registry::get('Zend_Acl');
        } else {
            $acl = new Zend_Acl();
        }

        // Process the config
        $resources = $this->getBootstrap()->getOption('resources');
        if (isset($resources['acl'])) {
            $options = $resources['acl'];
            // Roles
            foreach ($options['roles'] as $role => $info) {
                $acl->addRole(new Zend_Acl_Role($role));
            }
            // Resources
            ksort($options['resources']);
            foreach ($options['resources'] as $resource => $info) {
                if (($pos = strrpos($resource, '_')) !== false) {
                    $parent = substr($resource, 0, $pos);
                } else {
                    $parent = null;
                }
                $acl->add(new Zend_Acl_Resource($resource), $parent);
            }
            // Deny rules        
            foreach ($options['deny'] as $role => $deny) {
                foreach ($deny as $rule) {
                    // Get the resource
                    $resource = isset($rule['resource']) ? trim($rule['resource']) : null;
                    if (strtolower($resource) == 'null' || !$resource) {
                        $resource = null;
                    }
                    // Get the privilege
                    $privilege = isset($rule['privilege']) ? trim($rule['privilege']) : null;
                    if (strtolower($privilege) == 'null' || !$privilege) {
                        $privilege = null;
                    }
                    if (!is_null($privilege)) {
                        $privilege = explode(',', $privilege);
                    }
                    $acl->deny($role, $resource, $privilege);
                }
            }
            // Allow rules
            foreach ($options['allow'] as $role => $allow) {
                foreach ($allow as $rule) {
                    // Get the resource
                    $resource = isset($rule['resource']) ? trim($rule['resource']) : null;
                    if (strtolower($resource) == 'null') {
                        $resource = null;
                    }
                    // Get the privilege
                    $privilege = isset($rule['privilege']) ? trim($rule['privilege']) : null;
                    if (strtolower($privilege) == 'null') {
                        $privilege = null;
                    }
                    if (!is_null($privilege)) {
                        $privilege = explode(',', $privilege);
                    }
                    $acl->allow($role, $resource, $privilege);
                }
            }
        }
    
        // Store the acl back in the registry
        Zend_Registry::set('Zend_Acl', $acl);
        
        // return it
        return $acl;
    }
    
}
?>