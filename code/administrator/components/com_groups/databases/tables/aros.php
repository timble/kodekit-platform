<?php
class ComGroupsDatabaseTableAros extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'name' => 'core_acl_aro'
        ));
        
        parent::_initialize($config);
    }
}