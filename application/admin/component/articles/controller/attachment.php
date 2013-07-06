<?php

use Nooku\Library;

class ArticlesControllerAttachment extends AttachmentsControllerAttachment
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model'   => 'com:attachments.model.attachments',
            'request' => array(
                'view' => 'attachment'
            )
        ));

        parent::_initialize($config);
    }
}