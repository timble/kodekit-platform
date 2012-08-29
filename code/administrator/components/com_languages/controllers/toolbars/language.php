<?php
class ComLanguagesControllerToolbarLanguage extends ComDefaultControllerToolbarDefault
{
    public function onAfterControllerBrowse(KEvent $event)
    {    
        parent::onAfterControllerBrowse($event);

        $this->addSeparator();
        $this->addEnable();
        $this->addDisable();
    }
}