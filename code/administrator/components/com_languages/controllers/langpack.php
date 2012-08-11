<?php

class ComLanguagesControllerLangpack extends ComDefaultControllerDefault
{
    protected function _initialize(KConfig $config)
    {
    	$config->readonly = true;
        parent::_initialize($config);
    }
}