<?php
class ComLanguagesControllerBehaviorTranslatable extends KDatabaseBehaviorAbstract
{
    protected function _beforeControllerGet(KCommandContext $context)
    {
        if($this->getService('application')->getCfg('multilanguage'))
        {
            $state = $this->getModel()->getState();
            if(!isset($state->translatable)) {
                $state->insert('translatable', 'int');
            }
        }
    }
}