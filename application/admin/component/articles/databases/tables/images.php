<?php
class ComArticlesDatabaseTableImages extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column' => 'attachments_attachment_id'
        ));

        parent::_initialize($config);
    }
}