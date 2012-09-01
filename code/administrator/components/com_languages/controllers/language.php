<?php
class ComLanguagesControllerLanguage extends ComDefaultControllerDefault
{
    public function getModel()
    {
        if(!$this->_model instanceof KModelAbstract)
        {
            $identifier = $this->setModel($this->_model);
            if($identifier->package == 'languages' && $identifier->name == 'languages')
            {
                $model = clone $this->getService($identifier);
                $model->reset()->set($this->getRequest());
                
                $this->_model = $model;
            }
        }
        
        return parent::getModel();
    }
}