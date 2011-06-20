<?php

class ComFilesToolbarButtonDelete extends KToolbarButtonAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
			'text' => JText::_('Delete'),
        	'icon' => 'icon-32-delete'
        ));
        parent::_initialize($config);
    }

    public function getLink()
    {
    	return '#';
    }
}