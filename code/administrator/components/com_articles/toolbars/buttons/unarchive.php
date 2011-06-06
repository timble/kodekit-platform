<?php
class ComArticlesToolbarButtonUnarchive extends ComDefaultToolbarButtonDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'icon' => 'icon-32-unarchive',
            'text' => 'Unarchive',
            'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{state:0}'
            )
        ));

        parent::_initialize($config);
    }
}