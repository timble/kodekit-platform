<?php
class ComLanguagesDatabaseTableLanguages extends KDatabaseTableAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'name'      => 'languages',
            'behaviors' => array(
                'koowa:database.behavior.sluggable' => array('columns' => array('name'))
            ),
            'filters'   => array(
                'iso_code'  => array('com://admin/languages.filter.iso'),
		    )
        ));

        parent::_initialize($config);
    }
}