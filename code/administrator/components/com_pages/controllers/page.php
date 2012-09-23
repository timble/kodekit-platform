<?php
class ComPagesControllerPage extends ComDefaultControllerDefault
{
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'behaviors' => array(
    		    'com://admin/pages.controller.behavior.closurable'
    	    )
    	));
    
    	parent::_initialize($config);
    }
}