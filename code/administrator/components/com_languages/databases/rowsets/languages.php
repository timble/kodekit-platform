<?php 

class ComLanguagesDatabaseRowsetLanguages extends KDatabaseRowsetAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->identity_column = 'iso_code';
      
        parent::_initialize($config);
    }
}