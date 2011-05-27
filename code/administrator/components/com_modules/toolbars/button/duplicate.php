<?php
/** $Id$ */

class ComModulesToolbarButtonDuplicate extends ComDefaultToolbarButtonDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'attribs'  => array(
                'data-action' => 'duplicate'
            )
        ));
        
        parent::_initialize($config);
    }
}