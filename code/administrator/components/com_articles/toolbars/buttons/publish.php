<?php
class ComArticlesToolbarButtonPublish extends ComDefaultToolbarButtonDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'icon' => 'icon-32-publish',
            'text' => 'Publish',
            'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{state:1}'
            )
        ));

        parent::_initialize($config);
    }
}