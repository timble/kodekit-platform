<?php
class ComCommentsDatabaseTableComments extends KDatabaseTableDefault
{
	protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'base'      => 'comments_comments',
    		'name'      => 'comments_view_comments',
    		'behaviors' => array('creatable', 'modifiable', 'lockable'),
    	    'filters'   => array(
                'text' => array('html', 'tidy')
            )
    	));
    	
		parent::_initialize($config);
    }
}