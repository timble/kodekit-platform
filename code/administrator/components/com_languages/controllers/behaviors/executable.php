<?php
class ComLanguagesControllerBehaviorExecutable extends ComDefaultControllerBehaviorExecutable
{
    public function canEdit()
    {
        $name = $this->getMixer()->getIdentifier()->name;
        
        return $name == 'component' ? true : parent::canEdit();
    }
}