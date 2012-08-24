<?php
class ComLanguagesControllerBehaviorExecutable extends ComDefaultControllerBehaviorExecutable
{
    public function canEdit()
    {
        if($this->getMixer()->getIdentifier()->name == 'component') {
            $result = true;
        } else {
            $result = parent::canEdit();
        }
        
        return $result;
    }
}