<?php

use Nooku\Framework;

class ComAttachmentsDatabaseTableAttachments extends Framework\DatabaseTableDefault
{
	protected function _initialize(Framework\Config $config)
    {
    	$config->append(array(
    		'name'      => 'attachments',
    		'behaviors' => array('creatable', 'modifiable', 'lockable'),
    	    'filters'   => array(
                'text' => array('html', 'tidy')
            )
    	));
    	
		parent::_initialize($config);
    }
}