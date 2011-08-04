<?php
class ComGroupsDatabaseTableArosgroups extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'name' => 'core_acl_groups_aro_map'
        ));
        
        parent::_initialize($config);
    }
}