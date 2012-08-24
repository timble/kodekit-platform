<?php
class ComLanguagesControllerLanguage extends ComDefaultControllerDefault
{
    public function setModel($model)
    {
        parent::setModel($model);

        // Clone and reset the model to avoid state changes because of the singleton model.
        $this->_model = clone $this->getModel();
        $this->_model->reset();
        
        return $this->_model;
    }
}