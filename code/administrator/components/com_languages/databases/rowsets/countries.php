<?php 

class ComLanguagesDatabaseRowsetCountries extends KDatabaseRowsetAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->identity_column = 'code';
      
        parent::_initialize($config);
    }
}