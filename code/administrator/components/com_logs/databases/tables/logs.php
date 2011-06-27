<?php
class ComLogsDatabaseTableLogs extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'behaviors' => array('creatable')
        ));

        parent::_initialize($config);
    }
}