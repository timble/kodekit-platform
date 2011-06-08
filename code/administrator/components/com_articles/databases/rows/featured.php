<?php
class ComArticlesDatabaseRowFeatured extends KDatabaseRowDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'table' => 'admin::com.articles.database.table.featured'
        ));

        parent::_initialize($config);
    }
}