<?php

class ComFilesDatabaseRowsetNodes extends KDatabaseRowsetAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column' => 'path'
        ));

        parent::_initialize($config);
    }
}