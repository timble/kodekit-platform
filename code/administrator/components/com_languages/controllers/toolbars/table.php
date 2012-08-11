<?php
class ComLanguagesControllerToolbarTable extends ComDefaultControllerToolbarDefault
{
    public function onAfterControllerBrowse(KEvent $event)
    {
        parent::onAfterControllerBrowse($event);
        
        if($this->getController()->getModel()->getState()->translated !== false)
        {
            $this->reset()
                ->addDelete()
                ->addSeparator()
                ->addEnable(array('label' => 'publish'))
                ->addDisable(array('label' => 'unpublish'));
        }
        else $this->reset()->addCommand('add');
    }
}